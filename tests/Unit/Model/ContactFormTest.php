<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 17.05.18
 * Time: 15:26
 */

namespace Tests\Unit\Model;

use App\Base\Repository\MessageTemplateRepository;
use Broker\Domain\Entity\Message;
use App\Model\Contact;
use App\Model\ContactForm;
use Broker\Domain\Factory\MessageFactory;
use Broker\Domain\Service\MessageDeliveryService;
use Broker\System\BrokerInstance;
use PHPUnit\Framework\TestCase;
use Slim\Container;

class ContactFormTest extends TestCase
{
  private $templateRepoMock;
  private $deliveryServiceMock;
  private $mock;
  private $modelMock;
  private $containerMock;

  public function setUp()
  {
    $this->containerMock = $this->createMock(Container::class);
    $this->templateRepoMock = $this->createMock(MessageTemplateRepository::class);
    $this->deliveryServiceMock = $this->createMock(MessageDeliveryService::class);
    $this->mock = $this->getMockBuilder(ContactForm::class)
      ->disableOriginalConstructor()
      ->setMethods(['getModel', 'getMessageDeliveryService', 'getMessageTemplateRepository', 'getFormRecipient'])
      ->getMock();
    $this->modelMock = $this->createMock(Contact::class, ['load', 'validate']);
  }

  public function test__construct()
  {
    $instance = new ContactForm($this->createMock(BrokerInstance::class), $this->templateRepoMock, $this->deliveryServiceMock);

    $this->assertInstanceOf(MessageTemplateRepository::class, $instance->getMessageTemplateRepository());
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
    $this->templateRepoMock->method('getContactFormMessage')
      ->willReturn(new Message());
    $this->mock->method('getMessageTemplateRepository')
      ->willReturn($this->templateRepoMock);

    $this->deliveryServiceMock->method('setMessage')
      ->willReturnSelf();
    $this->deliveryServiceMock->method('run')
      ->willReturn(false);
    $this->mock->method('getMessageDeliveryService')
      ->willReturn($this->deliveryServiceMock);
    $this->mock->method('getModel')
      ->willReturn((new Contact)->setMessage('bööö'));
    $this->mock->method('getFormRecipient')
      ->willReturn('some@email.ee');

    $this->assertFalse($this->mock->send());
  }

  public function testSend()
  {
    $this->templateRepoMock->method('getContactFormMessage')
      ->willReturn(new Message());
    $this->mock->method('getMessageTemplateRepository')
      ->willReturn($this->templateRepoMock);

    $this->deliveryServiceMock->method('setMessage')
      ->willReturnSelf();
    $this->deliveryServiceMock->method('run')
      ->willReturn(true);
    $this->mock->method('getMessageDeliveryService')
      ->willReturn($this->deliveryServiceMock);
    $this->mock->method('getModel')
      ->willReturn((new Contact)->setMessage('bööö'));
    $this->mock->method('getFormRecipient')
      ->willReturn('some@email.ee');

    $this->assertTrue($this->mock->send());
  }
}
