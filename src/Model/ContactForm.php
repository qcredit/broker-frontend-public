<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 17.05.18
 * Time: 13:19
 */

namespace App\Model;

use App\Base\Validator\ContactValidator;
use Broker\Domain\Interfaces\Factory\MessageFactoryInterface;
use Broker\Domain\Interfaces\Repository\MessageTemplateRepositoryInterface;
use Broker\Domain\Interfaces\Service\MessageDeliveryServiceInterface;
use Broker\Domain\Entity\Message;
use Broker\System\BrokerInstance;
use Slim\Container;

class ContactForm
{
  const ATTR_NAME = 'name';
  const ATTR_EMAIL = 'email';
  const ATTR_MESSAGE = 'message';
  const ATTR_RECAPTCHA = 'g-recaptcha-response';

  /**
   * @var Contact
   */
  private $model;
  /**
   * @var MessageTemplateRepositoryInterface
   */
  private $messageTemplateRepository;
  /**
   * @var MessageDeliveryServiceInterface
   */
  private $messageDeliveryService;
  /**
   * @var Container
   */
  private $container;

  /**
   * @return Contact
   * @codeCoverageIgnore
   */
  public function getModel()
  {
    return $this->model;
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
   * @return MessageDeliveryServiceInterface
   * @codeCoverageIgnore
   */
  public function getMessageDeliveryService()
  {
    return $this->messageDeliveryService;
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
   * @return array
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getSettings()
  {
    return $this->getContainer()->get('settings');
  }

  /**
   * @return mixed|null
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getFormRecipient()
  {
    $settings = $this->getSettings();
    return $settings['mainEmail'] ?? '';
  }

  /**
   * ContactForm constructor.
   * @param Container $container
   * @param BrokerInstance $instance
   * @param MessageTemplateRepositoryInterface $messageTemplateRepository
   * @param MessageDeliveryServiceInterface $messageDeliveryService
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function __construct(
    Container $container,
    BrokerInstance $instance,
    MessageTemplateRepositoryInterface $messageTemplateRepository,
    MessageDeliveryServiceInterface $messageDeliveryService)
  {
    $this->container = $container;
    $this->model = new Contact();
    $this->model->setValidator(new ContactValidator($instance));
    $this->messageTemplateRepository = $messageTemplateRepository;
    $this->messageDeliveryService = $messageDeliveryService;
  }

  /**
   * @return bool
   * @throws \Broker\System\Error\InvalidConfigException
   * @throws \Exception
   */
  public function validate()
  {
    return $this->getModel()->validate();
  }

  /**
   * @param array $data
   * @return bool
   * @throws \Exception
   */
  public function load(array $data)
  {
    return $this->getModel()->load($data);
  }

  /**
   * @return bool
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function send()
  {
    $data = [
      'name' => $this->getModel()->getName(),
      'email' => $this->getModel()->getEmail(),
      'message' => $this->getModel()->getMessage()
    ];

    $message = $this->getMessageTemplateRepository()->getContactFormMessage($data, $this->getFormRecipient());

    if (!$this->getMessageDeliveryService()->setMessage($message)->run())
    {
      $this->getModel()->setErrors(['general' => _('We were unable to send your message. Please try again later.')]);
      return false;
    }

    return true;
  }
}
