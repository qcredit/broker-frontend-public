<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 07.06.18
 * Time: 09:33
 */

namespace App\Cron;

use App\Base\Validator\ApplicationValidator;
use App\Model\ApplicationForm;
use Broker\Domain\Entity\Application;
use Broker\Domain\Interfaces\Repository\ApplicationRepositoryInterface;
use Broker\Domain\Interfaces\Repository\MessageTemplateRepositoryInterface;
use Broker\Domain\Interfaces\Service\MessageDeliveryServiceInterface;
use Broker\Domain\Interfaces\System\LoggerInterface;
use Slim\Container;
use Broker\Domain\Entity\Message;

class SendFormReminders implements BaseJob
{
  /**
   * @var MessageDeliveryServiceInterface
   */
  protected $messageDeliveryService;
  /**
   * @var MessageTemplateRepositoryInterface
   */
  protected $messageTemplateRepository;
  /**
   * @var ApplicationRepositoryInterface
   */
  protected $applicationRepository;
  /**
   * @var Container
   */
  protected $container;
  /**
   * @var ApplicationValidator
   */
  protected $applicationValidator;

  /**
   * @return MessageDeliveryServiceInterface
   * @codeCoverageIgnore
   */
  public function getMessageDeliveryService()
  {
    return $this->messageDeliveryService;
  }

  /**
   * @return MessageTemplateRepositoryInterface
   * @codeCoverageIgnore
   */
  public function getMessageTemplateRepository()
  {
    return $this->messageTemplateRepository;
  }

  /**
   * @return ApplicationRepositoryInterface
   * @codeCoverageIgnore
   */
  public function getApplicationRepository()
  {
    return $this->applicationRepository;
  }

  /**
   * @return Container
   * @codeCoverageIgnore
   */
  public function getContainer()
  {
    return $this->container;
  }

  /**
   * @return LoggerInterface
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getLogger()
  {
    return $this->getContainer()->get('BrokerInstance')->getLogger()->withName('CRON');
  }

  /**
   * @return ApplicationValidator
   * @codeCoverageIgnore
   */
  public function getApplicationValidator()
  {
    return $this->applicationValidator;
  }

  /**
   * SendFormReminders constructor.
   * @param Container $container
   * @param MessageDeliveryServiceInterface $messageDeliveryService
   * @param MessageTemplateRepositoryInterface $messageTemplateRepository
   * @param ApplicationRepositoryInterface $applicationRepository
   * @param ApplicationValidator $applicationValidator
   */
  public function __construct(
    Container $container,
    MessageDeliveryServiceInterface $messageDeliveryService,
    MessageTemplateRepositoryInterface $messageTemplateRepository,
    ApplicationRepositoryInterface $applicationRepository,
    ApplicationValidator $applicationValidator
  )
  {
    $this->container = $container;
    $this->messageTemplateRepository = $messageTemplateRepository;
    $this->messageDeliveryService = $messageDeliveryService;
    $this->applicationRepository = $applicationRepository;
    $this->applicationValidator = $applicationValidator;
  }

  public function run(): bool
  {
    $this->getLogger()->debug(sprintf('Running %s...', get_class($this)));

    $applications = $this->getApplications();
    if (empty($applications))
    {
      $this->getLogger()->info('No applications found for form reminders!');
      return true;
    }

    $this->sendReminders($applications);

    return true;
  }

  /**
   * @return array
   */
  protected function getApplications()
  {
    return $this->getApplicationRepository()->getAppsNeedingFormReminders();
  }

  protected function sendReminders($applications)
  {
    foreach ($applications as $application)
    {
      if ($this->appIsValid($application)) continue;

      if ($this->appHasValidPhone($application) && $this->appNeedsSms($application))
      {
        $this->sendSmsReminder($application);
      }

      if ($this->appHasValidEmail($application) && $this->appNeedsEmail($application))
      {
        $this->sendEmailReminder($application);
      }
    }
  }

  /**
   * @param $application
   * @return bool
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function appHasValidPhone($application)
  {
    $validator = $this->getApplicationValidator();
    $validator->setValidationAttributes([ApplicationForm::ATTR_PHONE])
      ->setEntity($application);

    if (!$validator->validate())
    {
      $this->getLogger()->debug('Application does not have a valid phone.', ['appId' => $application->getId()]);
      return false;
    }

    return true;
  }

  /**
   * @param $application
   * @return bool
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function appHasValidEmail($application)
  {
    $validator = $this->getApplicationValidator();
    $validator->setValidationAttributes([ApplicationForm::ATTR_EMAIL])
      ->setEntity($application);

    if (!$validator->validate())
    {
      $this->getLogger()->debug('Application does not have a valid email.', ['appId' => $application->getId()]);
      return false;
    }

    return true;
  }

  /**
   * @param Application $application
   * @return bool
   */
  protected function appNeedsSms(Application $application)
  {
    $date = new \DateTime();
    $interval = $date->diff($application->getCreatedAt(), true);

    return ((int) $interval->format('%i') == 5) && ($application->getAttribute('form_sms_reminder_sent') == null) ? true : false;
  }

  /**
   * @param Application $application
   * @return bool
   */
  protected function appNeedsEmail(Application $application)
  {
    $date = new \DateTime();
    $interval = $date->diff($application->getCreatedAt(), true);

    return ((int) $interval->format('%i') == 10) ? true : false;
  }

  /**
   * @param Application $application
   * @return bool
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function sendEmailReminder(Application $application)
  {
    $this->getLogger()->info('Sending e-mail reminder for unfinished application', ['appId' => $application->getId()]);

    $message = $this->getMessageTemplateRepository()->getFormEmailReminderMessage($application);

    if ($this->sendReminder($message))
    {
      $this->updateApplication($application, $message->getType());
      return true;
    }

    return false;
  }

  /**
   * @param Application $application
   * @return bool
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function sendSmsReminder(Application $application)
  {
    $this->getLogger()->info('Sending SMS reminder for unfinished application', ['appId' => $application->getId()]);

    $message = $this->getMessageTemplateRepository()->getFormSmsReminderMessage($application);

    if ($this->sendReminder($message))
    {
      $this->updateApplication($application, $message->getType());
      return true;
    }

    return false;
  }

  /**
   * @param Application $application
   * @param $type
   * @return bool
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function updateApplication(Application $application, $type)
  {
    if ($type === Message::MESSAGE_TYPE_SMS)
    {
      $application->setDataElement('form_sms_reminder_sent', new \DateTime());
    }
    if ($type === Message::MESSAGE_TYPE_EMAIL)
    {
      $application->setDataElement('form_email_reminder_sent', new \DateTime());
    }

    $this->getLogger()->info('Updating application after sending reminders...', ['appId' => $application->getId()]);

    return $this->getApplicationRepository()->save($application);
  }

  /**
   * @param Message $message
   * @return bool
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function sendReminder(Message $message)
  {
    if ($this->getMessageDeliveryService()->setMessage($message)->run())
    {
      return true;
    }

    return false;
  }

  /**
   * @param Application $application
   * @return bool
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function appIsValid(Application $application)
  {
    $validator = $this->getApplicationValidator();
    $validator->setEntity($application);

    if ($validator->validate())
    {
      $this->getLogger()->debug('Application is valid, skipping it...', ['appId' => $application->getId()]);
      return true;
    }

    return false;
  }
}