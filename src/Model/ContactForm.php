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
use Broker\Domain\Interfaces\Service\MessageDeliveryServiceInterface;
use Broker\Domain\Entity\Message;

class ContactForm
{
  const ATTR_NAME = 'name';
  const ATTR_EMAIL = 'email';
  const ATTR_MESSAGE = 'message';
  /**
   * @var Contact
   */
  private $model;
  /**
   * @var MessageFactoryInterface
   */
  private $messageFactory;
  /**
   * @var MessageDeliveryServiceInterface
   */
  private $messageDeliveryService;
  /**
   * @return Contact
   * @codeCoverageIgnore
   */
  public function getModel()
  {
    return $this->model;
  }

  /**
   * @return MessageFactoryInterface
   * @codeCoverageIgnore
   */
  public function getMessageFactory()
  {
    return $this->messageFactory;
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
   * ContactForm constructor.
   * @param MessageFactoryInterface $messageFactory
   * @param MessageDeliveryServiceInterface $messageDeliveryService
   */
  public function __construct(MessageFactoryInterface $messageFactory, MessageDeliveryServiceInterface $messageDeliveryService)
  {
    $this->model = new Contact();
    $this->model->setValidator(new ContactValidator());
    $this->messageFactory = $messageFactory;
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
   * @return mixed
   */
  public function send()
  {
    $message = $this->getMessageFactory()->create();
    $message->setType(Message::MESSAGE_TYPE_EMAIL)
      ->setRecipient('qcredit.test@gmail.com')
      ->setBody($this->getModel()->getMessage())
      ->setTitle(_('Message from website'));

    if (!$this->getMessageDeliveryService()->setMessage($message)->run())
    {
      $this->getModel()->setErrors(['general' => _('We were unable to send your message. Please try again later.')]);
      return false;
    }

    return true;
  }
}