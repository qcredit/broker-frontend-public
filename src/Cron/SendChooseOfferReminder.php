<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 24.04.18
 * Time: 10:36
 */

namespace App\Cron;

use Broker\Domain\Interfaces\Repository\MessageTemplateRepositoryInterface;
use Broker\Domain\Entity\Application;
use Broker\Domain\Entity\Message;
use Broker\Domain\Interfaces\Factory\MessageFactoryInterface;
use Broker\Domain\Interfaces\Repository\ApplicationRepositoryInterface;
use Broker\Domain\Interfaces\Service\MessageDeliveryServiceInterface;
use Broker\System\Log;
use Slim\Container;

class SendChooseOfferReminder implements BaseJob
{
  /**
   * @var MessageDeliveryServiceInterface
   */
  protected $messageDeliveryService;
  /**
   * @var ApplicationRepositoryInterface
   */
  protected $applicationRepository;
  /**
   * @var MessageTemplateRepositoryInterface
   */
  protected $messageTemplateRepository;
  /**
   * @var Container
   */
  protected $container;

  /**
   * SendChooseOfferReminder constructor.
   * @param Container $container
   * @param MessageDeliveryServiceInterface $messageDeliveryService
   * @param ApplicationRepositoryInterface $applicationRepository
   * @param MessageTemplateRepositoryInterface $messageTemplateRepository
   */
  public function __construct(
    Container $container,
    MessageDeliveryServiceInterface $messageDeliveryService,
    ApplicationRepositoryInterface $applicationRepository,
    MessageTemplateRepositoryInterface $messageTemplateRepository
  )
  {
    $this->container = $container;
    $this->messageDeliveryService = $messageDeliveryService;
    $this->applicationRepository = $applicationRepository;
    $this->messageTemplateRepository = $messageTemplateRepository;
  }

  /**
   * @return MessageDeliveryServiceInterface
   * @codeCoverageIgnore
   */
  public function getMessageDeliveryService()
  {
    return $this->messageDeliveryService;
  }

  /**
   * @param MessageDeliveryServiceInterface $messageDeliveryService
   * @return SendChooseOfferReminder
   * @codeCoverageIgnore
   */
  public function setMessageDeliveryService(MessageDeliveryServiceInterface $messageDeliveryService)
  {
    $this->messageDeliveryService = $messageDeliveryService;
    return $this;
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
   * @param ApplicationRepositoryInterface $applicationRepository
   * @return SendChooseOfferReminder
   * @codeCoverageIgnore
   */
  public function setApplicationRepository(ApplicationRepositoryInterface $applicationRepository)
  {
    $this->applicationRepository = $applicationRepository;
    return $this;
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
   * @param MessageTemplateRepositoryInterface $messageTemplateRepository
   * @return SendChooseOfferReminder
   * @codeCoverageIgnore
   */
  public function setMessageTemplateRepository(MessageTemplateRepositoryInterface $messageTemplateRepository)
  {
    $this->messageTemplateRepository = $messageTemplateRepository;
    return $this;
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
   * @param Container $container
   * @return SendChooseOfferReminder
   * @codeCoverageIgnore
   */
  public function setContainer(Container $container)
  {
    $this->container = $container;
    return $this;
  }

  /**
   * @return mixed
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getLogger()
  {
    return $this->getContainer()->get('BrokerInstance')->getLogger();
  }

  /**
   * @return bool
   * @throws \Exception
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function run(): bool
  {
    $this->getLogger()->debug(sprintf('Running %s...', get_class($this)));
    $apps = $this->getApplicationRepository()->getAppsNeedingReminder();

    if (empty($apps))
    {
      $this->getLogger()->info('No applications found for offer reminders!');
    }

    foreach ($apps as $app)
    {
      $this->sendNeededReminders($app);
    }

    return true;
  }

  /**
   * @param Application $app
   * @throws \Exception
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function sendNeededReminders(Application $app)
  {
    $this->getLogger()->debug('Preparing to send application reminders...', ['appId' => $app->getId()]);

    $this->sendEmailReminder($app);
    if ($app->getDataElement('sms_reminder_sent') === null)
    {
      $this->sendSmsReminder($app);
    }
  }

  /**
   * @param Application $app
   * @return bool
   * @throws \Exception
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function sendEmailReminder(Application $app)
  {
    $this->getLogger()->info('Sending e-mail reminder for application', ['appId' => $app->getId()]);

    $message = $this->getMessageTemplateRepository()->getOfferReminderMessage($app);

    if ($this->sendReminder($message))
    {
      $this->updateApplication($app, $message->getType());
      return true;
    }

    return false;
  }

  /**
   * @param Application $app
   * @return bool
   * @throws \Exception
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function sendSmsReminder(Application $app)
  {
    $this->getLogger()->info('Sending sms reminder for app...', ['appId' => $app->getId()]);

    $message = $this->getMessageTemplateRepository()->getOfferReminderSmsMessage($app);

    if ($this->sendReminder($message))
    {
      $this->updateApplication($app, $message->getType());
      return true;
    }

    return false;
  }

  /**
   * @param Message $message
   * @return bool
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function sendReminder(Message $message)
  {
    if (($message->getType() === Message::MESSAGE_TYPE_SMS)
      && $this->getContainer()->get('settings')['broker']['environment'] !== 'production'
      && strpos($message->getRecipient(), '@') == false
    )
    {
      $this->getLogger()->debug('Skipping sending reminder for current environment...');
      return true;
    }

    if (strpos($message->getRecipient(), '@') !== false)
    {
      $recipient = substr($message->getRecipient(), strpos($message, '@') + 1);
      $message->setRecipient($recipient);
    }

    if ($this->getMessageDeliveryService()->setMessage($message)->run())
    {
      return true;
    }

    return false;
  }

  /**
   * @param Application $app
   * @param string $messageType
   * @return mixed
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function updateApplication(Application $app, string $messageType)
  {
    if ($messageType === Message::MESSAGE_TYPE_SMS)
    {
      $app->setDataElement('sms_reminder_sent', new \DateTime());
    }
    if ($messageType === Message::MESSAGE_TYPE_EMAIL)
    {
      $app->setDataElement('email_reminder_sent', new \DateTime());
    }

    $this->getLogger()->info('Updating application after sending reminders...', ['appId' => $app->getId()]);

    return $this->getApplicationRepository()->save($app);
  }
}