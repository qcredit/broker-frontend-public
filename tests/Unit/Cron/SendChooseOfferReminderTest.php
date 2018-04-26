<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 26.04.18
 * Time: 12:20
 */

namespace Tests\Unit\Cron;

use App\Base\Persistence\Doctrine\ApplicationRepository;
use App\Base\Repository\MessageTemplateRepository;
use App\Cron\SendChooseOfferReminder;
use Broker\Domain\Factory\MessageFactory;
use Broker\Domain\Service\MessageDeliveryService;
use Broker\System\BaseTest;
use Broker\Domain\Entity\Application;
use Broker\Domain\Entity\Message;

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
        'getApplicationRepository'
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

    $this->deliveryServiceMock->expects($this->once())
      ->method('run')
      ->willReturn(true);
    $this->mock->expects($this->atLeastOnce())
      ->method('getMessageDeliveryService')
      ->willReturn($this->deliveryServiceMock);
    $this->mock->expects($this->once())
      ->method('getMessageTemplateRepository')
      ->willReturn($this->templateRepositoryMock);

    $app = (new Application())->setPhone('+371325032');

    $result = $this->invokeMethod($this->mock, 'sendSmsReminder', [$app]);
    $this->assertTrue($result);
    $this->assertSame($app->getPhone(), $this->mock->getMessageDeliveryService()->getMessage()->getRecipient());
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
