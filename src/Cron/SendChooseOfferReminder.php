<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 24.04.18
 * Time: 10:36
 */

namespace App\Cron;

use App\Base\Interfaces\MessageTemplateRepositoryInterface;
use Broker\Domain\Entity\Application;
use Broker\Domain\Entity\Message;
use Broker\Domain\Interfaces\Factory\MessageFactoryInterface;
use Broker\Domain\Interfaces\Repository\ApplicationRepositoryInterface;
use Broker\Domain\Interfaces\Service\MessageDeliveryServiceInterface;
use Broker\System\Log;

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
   * @var MessageFactoryInterface
   */
  protected $messageFactory;
  /**
   * @var MessageTemplateRepositoryInterface
   */
  protected $messageTemplateRepository;

  /**
   * SendChooseOffer constructor.
   * @param MessageDeliveryServiceInterface $messageDeliveryService
   * @param ApplicationRepositoryInterface $applicationRepository
   * @param MessageFactoryInterface $messageFactory
   * @param MessageTemplateRepositoryInterface $messageTemplateRepository
   */
  public function __construct(
    MessageDeliveryServiceInterface $messageDeliveryService,
    ApplicationRepositoryInterface $applicationRepository,
    MessageFactoryInterface $messageFactory,
    MessageTemplateRepositoryInterface $messageTemplateRepository
  )
  {
    $this->messageDeliveryService = $messageDeliveryService;
    $this->applicationRepository = $applicationRepository;
    $this->messageFactory = $messageFactory;
    $this->messageTemplateRepository = $messageTemplateRepository;
  }

  /**
   * @return MessageDeliveryServiceInterface
   */
  public function getMessageDeliveryService()
  {
    return $this->messageDeliveryService;
  }

  /**
   * @param MessageDeliveryServiceInterface $messageDeliveryService
   * @return SendChooseOfferReminder
   */
  public function setMessageDeliveryService(MessageDeliveryServiceInterface $messageDeliveryService)
  {
    $this->messageDeliveryService = $messageDeliveryService;
    return $this;
  }

  /**
   * @return ApplicationRepositoryInterface
   */
  public function getApplicationRepository()
  {
    return $this->applicationRepository;
  }

  /**
   * @param ApplicationRepositoryInterface $applicationRepository
   * @return SendChooseOfferReminder
   */
  public function setApplicationRepository(ApplicationRepositoryInterface $applicationRepository)
  {
    $this->applicationRepository = $applicationRepository;
    return $this;
  }

  /**
   * @return MessageFactoryInterface
   */
  public function getMessageFactory()
  {
    return $this->messageFactory;
  }

  /**
   * @param MessageFactoryInterface $messageFactory
   * @return SendChooseOfferReminder
   */
  public function setMessageFactory(MessageFactoryInterface $messageFactory)
  {
    $this->messageFactory = $messageFactory;
    return $this;
  }

  /**
   * @return MessageTemplateRepositoryInterface
   */
  public function getMessageTemplateRepository()
  {
    return $this->messageTemplateRepository;
  }

  /**
   * @param MessageTemplateRepositoryInterface $messageTemplateRepository
   * @return SendChooseOfferReminder
   */
  public function setMessageTemplateRepository(MessageTemplateRepositoryInterface $messageTemplateRepository)
  {
    $this->messageTemplateRepository = $messageTemplateRepository;
    return $this;
  }

  /**
   * @return bool
   * @throws \Exception
   */
  public function run(): bool
  {
    $apps = $this->getApplicationRepository()->getAppsNeedingReminder();

    foreach ($apps as $app)
    {
      $this->sendNeededReminders($app);
    }

    return true;
  }

  /**
   * @param Application $app
   * @throws \Exception
   */
  protected function sendNeededReminders(Application $app)
  {
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
   */
  protected function sendEmailReminder(Application $app)
  {
    Log::info(sprintf('Sending e-mail reminder for app #%s', $app->getId()));
    $message = $this->getMessageFactory()->create();
    $message->setType(Message::MESSAGE_TYPE_EMAIL)
      ->setRecipient($app->getEmail())
      ->setBody($this->getMessageTemplateRepository()->getTemplateByPath('mail/offer-reminder.twig', ['application' => $app]));

    if ($this->getMessageDeliveryService()->setMessage($message)->run())
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
   */
  protected function sendSmsReminder(Application $app)
  {
    Log::info(sprintf('Sending sms reminder for app #%s', $app->getId()));
    $message = $this->getMessageFactory()->create();
    $message->setType(Message::MESSAGE_TYPE_SMS)
      ->setRecipient($app->getPhone())
      ->setBody($this->getMessageTemplateRepository()->getTemplateByPath('sms/offer-reminder.twig', ['application' => $app]));

    if ($this->getMessageDeliveryService()->setMessage($message)->run())
    {
      $this->updateApplication($app, $message->getType());
      return true;
    }
  }

  /**
   * @param Application $app
   * @param string $messageType
   * @return mixed
   * @throws \Exception
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

    Log::info('Updating application after sending reminders...');

    return $this->getApplicationRepository()->save($app);
  }
}