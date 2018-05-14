<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 26.04.18
 * Time: 12:20
 */

namespace Tests\Unit\Cron;

use App\Base\Interfaces\MessageTemplateRepositoryInterface;
use App\Base\Persistence\Doctrine\ApplicationRepository;
use App\Base\Repository\MessageTemplateRepository;
use App\Cron\SendChooseOfferReminder;
use Broker\Domain\Factory\MessageFactory;
use Broker\Domain\Interfaces\Factory\MessageFactoryInterface;
use Broker\Domain\Interfaces\Repository\ApplicationRepositoryInterface;
use Broker\Domain\Interfaces\Service\MessageDeliveryServiceInterface;
use Broker\Domain\Service\MessageDeliveryService;
use Broker\System\BaseTest;
use Broker\Domain\Entity\Application;
use Broker\Domain\Entity\Message;
use Slim\Container;

class SendChooseOfferReminderTest extends BaseTest
{
  protected $mock;
  protected $appRepositoryMock;
  protected $factoryMock;
  protected $deliveryServiceMock;
  protected $templateRepositoryMock;
  protected $appArray;

  public function setUp()
  {
    $this->mock = $this->getMockBuilder(SendChooseOfferReminder::class)
      ->disableOriginalConstructor()
      ->setMethods([
        'updateApplication',
        'getMessageFactory',
        'getMessageDeliveryService',
        'getMessageTemplateRepository',
        'getApplicationRepository',
        'sendReminder'
      ])
      ->getMock();

    $this->appRepositoryMock = $this->getMockBuilder(ApplicationRepository::class)
      ->disableOriginalConstructor()
      ->setMethods([
        'getAppsNeedingReminder',
        'save'
      ])
      ->getMock();

    $this->factoryMock = $this->createMock(MessageFactory::class);

    $this->deliveryServiceMock = $this->getMockBuilder(MessageDeliveryService::class)
      ->disableOriginalConstructor()
      ->setMethods(['run'])
      ->getMock();

    $this->templateRepositoryMock = $this->createMock(MessageTemplateRepository::class);

    $this->appArray = [
      (new Application()),
      (new Application())->setDataElement('email_reminder_sent', new \DateTime()),
      (new Application())->setDataElement('sms_reminder_sent', new \DateTime())
      ->setDataElement('email_reminder_sent', new \DateTime())
    ];
  }

  public function test__construct()
  {
    $container = $this->createMock(Container::class);
    $instance = new SendChooseOfferReminder(
      $container,
      $this->deliveryServiceMock,
      $this->appRepositoryMock,
      $this->factoryMock,
      $this->templateRepositoryMock
    );

    $this->assertInstanceOf(Container::class, $instance->getContainer());
    $this->assertInstanceOf(MessageDeliveryServiceInterface::class, $instance->getMessageDeliveryService());
    $this->assertInstanceOf(ApplicationRepositoryInterface::class, $instance->getApplicationRepository());
    $this->assertInstanceOf(MessageFactoryInterface::class, $instance->getMessageFactory());
    $this->assertInstanceOf(MessageTemplateRepositoryInterface::class, $instance->getMessageTemplateRepository());
  }

  public function testRunWithNoRemindersSent()
  {
    $this->appRepositoryMock->expects($this->once())
      ->method('getAppsNeedingReminder')
      ->willReturn($this->appArray);

    $mock = $this->getMockBuilder(SendChooseOfferReminder::class)
      ->disableOriginalConstructor()
      ->setMethods(['sendNeededReminders', 'getApplicationRepository'])
      ->getMock();
    $mock->method('getApplicationRepository')
      ->willReturn($this->appRepositoryMock);
    $mock->expects($this->exactly(count($this->appArray)))
      ->method('sendNeededReminders')
      ->willReturn(true);

    $mock->run();
  }

  public function testSendNeededReminders()
  {
    $app = (new Application());

    $mock = $this->getMockBuilder(SendChooseOfferReminder::class)
      ->disableOriginalConstructor()
      ->setMethods(['sendEmailReminder', 'sendSmsReminder'])
      ->getMock();
    $mock->expects($this->once())
      ->method('sendEmailReminder');
    $mock->expects($this->once())
      ->method('sendSmsReminder');

    $this->invokeMethod($mock, 'sendNeededReminders', [$app]);
  }

  public function testSendNeededRemindersWithOneAlreadySent()
  {
    $app = (new Application())->setDataElement('sms_reminder_sent', new \DateTime());

    $mock = $this->getMockBuilder(SendChooseOfferReminder::class)
      ->disableOriginalConstructor()
      ->setMethods(['sendEmailReminder', 'sendSmsReminder'])
      ->getMock();
    $mock->expects($this->once())
      ->method('sendEmailReminder');
    $mock->expects($this->never())
      ->method('sendSmsReminder');

    $this->invokeMethod($mock, 'sendNeededReminders', [$app]);
  }

  public function testSendEmailReminder()
  {
    $this->factoryMock->expects($this->once())
      ->method('create')
      ->willReturn(new Message());
    $this->mock->expects($this->once())
      ->method('getMessageFactory')
      ->willReturn($this->factoryMock);
    $this->mock->expects($this->once())
      ->method('updateApplication')
      ->willReturn(true);
    $this->mock->expects($this->once())
      ->method('sendReminder')
      ->willReturn(true);

    $this->mock->expects($this->once())
      ->method('getMessageTemplateRepository')
      ->willReturn($this->templateRepositoryMock);

    $app = (new Application())->setEmail('paavst@vatikan.com');

    $result = $this->invokeMethod($this->mock, 'sendEmailReminder', [$app]);
    $this->assertTrue($result);
  }

  public function testSendEmailReminderFails()
  {
    $this->factoryMock->expects($this->once())
      ->method('create')
      ->willReturn(new Message());
    $this->mock->expects($this->once())
      ->method('getMessageFactory')
      ->willReturn($this->factoryMock);
    $this->mock->expects($this->never())
      ->method('updateApplication')
      ->willReturn(true);
    $this->mock->expects($this->once())
      ->method('sendReminder')
      ->willReturn(false);

    $this->mock->expects($this->once())
      ->method('getMessageTemplateRepository')
      ->willReturn($this->templateRepositoryMock);

    $app = (new Application())->setEmail('paavst@vatikan.com');

    $result = $this->invokeMethod($this->mock, 'sendEmailReminder', [$app]);
    $this->assertFalse($result);
  }

  public function testSendSmsReminder()
  {
    $this->factoryMock->expects($this->once())
      ->method('create')
      ->willReturn(new Message());
    $this->mock->expects($this->once())
      ->method('getMessageFactory')
      ->willReturn($this->factoryMock);
    $this->mock->expects($this->once())
      ->method('updateApplication')
      ->willReturn(true);
    $this->mock->expects($this->once())
      ->method('sendReminder')
      ->willReturn(true);

    $this->mock->expects($this->once())
      ->method('getMessageTemplateRepository')
      ->willReturn($this->templateRepositoryMock);

    $app = (new Application())->setPhone('+371325032');

    $result = $this->invokeMethod($this->mock, 'sendSmsReminder', [$app]);
    $this->assertTrue($result);
  }

  public function testSendSmsReminderFails()
  {
    $this->factoryMock->expects($this->once())
      ->method('create')
      ->willReturn(new Message());
    $this->mock->expects($this->once())
      ->method('getMessageFactory')
      ->willReturn($this->factoryMock);
    $this->mock->expects($this->never())
      ->method('updateApplication')
      ->willReturn(true);
    $this->mock->expects($this->once())
      ->method('sendReminder')
      ->willReturn(false);

    $this->mock->expects($this->once())
      ->method('getMessageTemplateRepository')
      ->willReturn($this->templateRepositoryMock);

    $app = (new Application())->setPhone('+371325032');

    $result = $this->invokeMethod($this->mock, 'sendSmsReminder', [$app]);
    $this->assertFalse($result);
  }

  public function testUpdateApplicationWithEmailUpdate()
  {
    $app = new Application();
    $type = Message::MESSAGE_TYPE_EMAIL;

    $this->appRepositoryMock->expects($this->once())
      ->method('save')
      ->will($this->returnArgument(0));

    $mock = $this->getMockBuilder(SendChooseOfferReminder::class)
      ->disableOriginalConstructor()
      ->setMethods(['getApplicationRepository'])
      ->getMock();
    $mock->method('getApplicationRepository')
      ->willReturn($this->appRepositoryMock);

    $result = $this->invokeMethod($mock, 'updateApplication', [$app, $type]);
    $this->assertInstanceOf(\DateTime::class, $result->getDataElement('email_reminder_sent'));
  }

  public function testSendReminder()
  {
    $message = new Message();
    $confArray = [
      'broker' => [
        'environment' => 'SPACE'
      ]
    ];

    $mock = $this->getMockBuilder(SendChooseOfferReminder::class)
      ->disableOriginalConstructor()
      ->setMethods(['getContainer', 'getMessageDeliveryService'])
      ->getMock();
    $this->deliveryServiceMock->expects($this->once())
      ->method('run')
      ->willReturn(true);
    $mock->expects($this->atLeastOnce())
      ->method('getMessageDeliveryService')
      ->willReturn($this->deliveryServiceMock);
    $containerMock = $this->getMockBuilder(Container::class)
      ->disableOriginalConstructor()
      ->setMethods(['get'])
      ->getMock();
    $containerMock->expects($this->once())
      ->method('get')
      ->willReturn($confArray);
    $mock->method('getContainer')
      ->willReturn($containerMock);

    $result = $this->invokeMethod($mock, 'sendReminder', [$message]);
    $this->assertTrue($result);
  }

  public function testSendReminderInTest()
  {
    $message = new Message();
    $confArray = [
      'broker' => [
        'environment' => 'testserver'
      ]
    ];

    $mock = $this->getMockBuilder(SendChooseOfferReminder::class)
      ->disableOriginalConstructor()
      ->setMethods(['getContainer', 'getMessageDeliveryService'])
      ->getMock();

    $containerMock = $this->getMockBuilder(Container::class)
      ->disableOriginalConstructor()
      ->setMethods(['get'])
      ->getMock();
    $containerMock->expects($this->once())
      ->method('get')
      ->willReturn($confArray);
    $mock->method('getContainer')
      ->willReturn($containerMock);

    $result = $this->invokeMethod($mock, 'sendReminder', [$message]);
    $this->assertTrue($result);
  }

  public function testSendReminderServiceFailing()
  {
    $message = new Message();
    $confArray = [
      'broker' => [
        'environment' => 'SPACE'
      ]
    ];

    $mock = $this->getMockBuilder(SendChooseOfferReminder::class)
      ->disableOriginalConstructor()
      ->setMethods(['getContainer', 'getMessageDeliveryService'])
      ->getMock();
    $this->deliveryServiceMock->expects($this->once())
      ->method('run')
      ->willReturn(false);
    $mock->expects($this->atLeastOnce())
      ->method('getMessageDeliveryService')
      ->willReturn($this->deliveryServiceMock);
    $containerMock = $this->getMockBuilder(Container::class)
      ->disableOriginalConstructor()
      ->setMethods(['get'])
      ->getMock();
    $containerMock->expects($this->once())
      ->method('get')
      ->willReturn($confArray);
    $mock->method('getContainer')
      ->willReturn($containerMock);

    $result = $this->invokeMethod($mock, 'sendReminder', [$message]);
    $this->assertFalse($result);
  }

  public function testUpdateApplicationWithSmsUpdate()
  {
    $app = new Application();
    $type = Message::MESSAGE_TYPE_SMS;

    $this->appRepositoryMock->expects($this->once())
      ->method('save')
      ->will($this->returnArgument(0));

    $mock = $this->getMockBuilder(SendChooseOfferReminder::class)
      ->disableOriginalConstructor()
      ->setMethods(['getApplicationRepository'])
      ->getMock();
    $mock->method('getApplicationRepository')
      ->willReturn($this->appRepositoryMock);

    $result = $this->invokeMethod($mock, 'updateApplication', [$app, $type]);
    $this->assertInstanceOf(\DateTime::class, $result->getDataElement('sms_reminder_sent'));
  }
}
