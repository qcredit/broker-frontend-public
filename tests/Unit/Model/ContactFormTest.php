<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 17.05.18
 * Time: 15:26
 */

namespace Tests\Unit\Model;

use Broker\Domain\Entity\Message;
use App\Model\Contact;
use App\Model\ContactForm;
use Broker\Domain\Factory\MessageFactory;
use Broker\Domain\Service\MessageDeliveryService;
use PHPUnit\Framework\TestCase;

class ContactFormTest extends TestCase
{
  private $factoryMock;
  private $deliveryServiceMock;
  private $mock;
  private $modelMock;

  public function setUp()
  {
    $this->factoryMock = $this->createMock(MessageFactory::class);
    $this->deliveryServiceMock = $this->createMock(MessageDeliveryService::class);
    $this->mock = $this->getMockBuilder(ContactForm::class)
      ->disableOriginalConstructor()
      ->setMethods(['getModel', 'getMessageDeliveryService', 'getMessageFactory'])
      ->getMock();
    $this->modelMock = $this->createMock(Contact::class, ['load', 'validate']);
  }

  public function test__construct()
  {
    $instance = new ContactForm($this->factoryMock, $this->deliveryServiceMock);

    $this->assertInstanceOf(MessageFactory::class, $instance->getMessageFactory());
    $this->assertInstanceOf(MessageDeliveryService::class, $instance->getMessageDeliveryService());
  }

  public function testValidateFalse()
  {
    $value = false;
    $this->modelMock->method('validate')
      ->willReturn($value);
    $this->mock->method('getModel')
      ->willReturn($this->modelMock);

    $this->assertSame($value, $this->mock->validate());
  }

  public function testValidateTrue()
  {
    $value = true;
    $this->modelMock->method('validate')
      ->willReturn($value);
    $this->mock->method('getModel')
      ->willReturn($this->modelMock);

    $this->assertSame($value, $this->mock->validate());
  }

  public function testLoad()
  {
    $this->modelMock->method('load')
      ->willReturnArgument(0);
    $this->mock->method('getModel')
      ->willReturn($this->modelMock);

    $data = ['some' => 'array'];
    $this->assertSame($data, $this->mock->load($data));
  }

  public function testSendFails()
  {
    $this->factoryMock->method('create')
      ->willReturn(new Message());
    $this->mock->method('getMessageFactory')
      ->willReturn($this->factoryMock);

    $this->deliveryServiceMock->method('setMessage')
      ->willReturnSelf();
    $this->deliveryServiceMock->method('run')
      ->willReturn(false);
    $this->mock->method('getMessageDeliveryService')
      ->willReturn($this->deliveryServiceMock);
    $this->mock->method('getModel')
      ->willReturn((new Contact)->setMessage('bööö'));

    $this->assertFalse($this->mock->send());
  }

  public function testSend()
  {
    $this->factoryMock->method('create')
      ->willReturn(new Message());
    $this->mock->method('getMessageFactory')
      ->willReturn($this->factoryMock);

    $this->deliveryServiceMock->method('setMessage')
      ->willReturnSelf();
    $this->deliveryServiceMock->method('run')
      ->willReturn(true);
    $this->mock->method('getMessageDeliveryService')
      ->willReturn($this->deliveryServiceMock);
    $this->mock->method('getModel')
      ->willReturn((new Contact)->setMessage('bööö'));

    $this->assertTrue($this->mock->send());
  }
}
