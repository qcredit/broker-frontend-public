<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 18.04.18
 * Time: 13:14
 */

namespace App\Base\Factory;

use App\Base\Components\EmailDelivery;
use App\Base\Components\SmsDelivery;
use Broker\Domain\Entity\Message;
use Broker\Domain\Interfaces\Factory\MessageDeliveryStrategyFactoryInterface;
use Broker\Domain\Interfaces\MessageDeliveryInterface;
use Broker\System\Error\InvalidConfigException;
use Slim\Container;

class MessageDeliveryStrategyFactory implements MessageDeliveryStrategyFactoryInterface
{
  /**
   * @var Container
   */
  protected $container;

  /**
   * @return Container
   */
  public function getContainer()
  {
    return $this->container;
  }

  /**
   * @param Container $container
   * @return MessageDeliveryStrategyFactory
   */
  public function setContainer($container)
  {
    $this->container = $container;
    return $this;
  }

  /**
   * MessageDeliveryStrategyFactory constructor.
   * @param Container $container
   */
  public function __construct(Container $container)
  {
    $this->container = $container;
  }

  /**
   * @param Message $message
   * @return MessageDeliveryInterface
   * @throws InvalidConfigException
   * @throws \Exception
   */
  public function create(Message $message): MessageDeliveryInterface
  {
    switch ($message->getType())
    {
      case Message::MESSAGE_TYPE_EMAIL:
        return new EmailDelivery($this->getContainer());
        break;
      case Message::MESSAGE_TYPE_SMS:
        return new SmsDelivery();
        break;
      default:
        throw new InvalidConfigException('Unknown message type!');
        break;
    }
  }
}