<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 18.04.18
 * Time: 13:19
 */

namespace Tests\Unit\Base\Factory;

use App\Base\Components\EmailDelivery;
use App\Base\Components\SmsDelivery;
use App\Base\Factory\MessageDeliveryStrategyFactory;
use Broker\Domain\Entity\Message;
use Broker\System\BaseTest;
use Broker\System\Error\InvalidConfigException;
use Slim\Container;

class MessageDeliveryStrategyFactoryTest extends BaseTest
{
  protected $containerMock;

  public function setUp()
  {
    $this->containerMock = $this->createMock(Container::class);
  }

  public function testCreateEmailDelivery()
  {
    $message = (new Message())->setType(Message::MESSAGE_TYPE_EMAIL);
    $instance = new MessageDeliveryStrategyFactory($this->containerMock);

    $this->assertInstanceOf(EmailDelivery::class, $instance->create($message));
  }

  public function testCreateSmsDelivery()
  {
    $message = (new Message())->setType(Message::MESSAGE_TYPE_SMS);
    $instance = new MessageDeliveryStrategyFactory($this->containerMock);

    $this->assertInstanceOf(SmsDelivery::class, $instance->create($message));
  }

  public function testCreateUnknownDelivery()
  {
    $message = (new Message())->setType(1337);
    $instance = new MessageDeliveryStrategyFactory($this->containerMock);

    $this->expectException(InvalidConfigException::class);

    $instance->create($message);
  }
}
