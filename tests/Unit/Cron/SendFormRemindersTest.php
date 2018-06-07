<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 07.06.18
 * Time: 09:42
 */

namespace Tests\Unit\Cron;

use App\Base\Validator\ApplicationValidator;
use App\Cron\SendFormReminders;
use Broker\Domain\Entity\Application;
use Broker\Domain\Entity\Message;
use Broker\Domain\Interfaces\Repository\ApplicationRepositoryInterface;
use Broker\Domain\Interfaces\Repository\MessageTemplateRepositoryInterface;
use Broker\Domain\Interfaces\Service\MessageDeliveryServiceInterface;
use Broker\System\BaseTest;
use Slim\App;
use Slim\Container;
use Tests\Helpers\LoggerMockTrait;

class SendFormRemindersTest extends BaseTest
{
  use LoggerMockTrait;

  protected $deliveryServiceMock;
  protected $templateRepoMock;
  protected $repositoryMock;
  protected $containerMock;
  protected $validatorMock;
  protected $mock;
  protected $altMock;

  public function setUp()
  {
    $this->setupMocks();

    $this->deliveryServiceMock = $this->createMock(MessageDeliveryServiceInterface::class);
    $this->templateRepoMock = $this->getMockBuilder(MessageTemplateRepositoryInterface::class)
      ->disableOriginalConstructor()
      ->setMethods(['getFormEmailReminderMessage', 'getFormSmsReminderMessage'])
      ->getMockForAbstractClass();
    $this->repositoryMock = $this->createMock(ApplicationRepositoryInterface::class);
    $this->containerMock = $this->createMock(Container::class);
    $this->validatorMock = $this->createMock(ApplicationValidator::class);
    $this->mock = $this->getMockBuilder(SendFormReminders::class)
      ->disableOriginalConstructor()
      ->setMethods([
        'getApplications',
        'getLogger',
        'getApplicationValidator',
        'appIsValid',
        'appNeedsSms',
        'appNeedsEmail',
        'sendEmailReminder',
        'sendSmsReminder',
        'sendReminder',
        'getMessageTemplateRepository',
        'appHasValidPhone',
        'appHasValidEmail'
      ])
      ->getMock();
    $this->altMock = $this->createMock(SendFormReminders::class);
    $this->mock->method('getLogger')
      ->willReturn($this->loggerMock);
  }

  public function test__construct()
  {
    $instance = new SendFormReminders(
      $this->containerMock,
      $this->deliveryServiceMock,
      $this->templateRepoMock,
      $this->repositoryMock,
      $this->validatorMock
      );

    $this->assertInstanceOf(Container::class, $instance->getContainer());
    $this->assertInstanceOf(MessageDeliveryServiceInterface::class, $instance->getMessageDeliveryService());
    $this->assertInstanceOf(MessageTemplateRepositoryInterface::class, $instance->getMessageTemplateRepository());
    $this->assertInstanceOf(ApplicationRepositoryInterface::class, $instance->getApplicationRepository());
  }

  public function testRunWithoutApplications()
  {
    $this->mock->expects($this->once())
      ->method('getApplications')
      ->willReturn([]);

    $this->assertTrue($this->mock->run());
  }

  public function testRunWithApplications()
  {
    $mock = $this->getMockBuilder(SendFormReminders::class)
      ->disableOriginalConstructor()
      ->setMethods(['sendReminders', 'getApplications', 'getLogger'])
      ->getMock();
    $mock->expects($this->once())
      ->method('sendReminders');

    $mock->method('getLogger')
      ->willReturn($this->loggerMock);
    $mock->method('getApplications')
      ->willReturn(['asd', 'asdasd']);

    $this->assertTrue($mock->run());
  }

  public function testSendRemindersWithValidApps()
  {
    $apps = [
      new Application(),
      new Application()
    ];

    $this->mock->expects($this->exactly(count($apps)))
      ->method('appIsValid')
      ->willReturn(true);

    $this->mock->expects($this->never())
      ->method('appNeedsSms');
    $this->mock->expects($this->never())
      ->method('appNeedsEmail');

    $this->invokeMethod($this->mock, 'sendReminders', [$apps]);
  }

  public function testSendRemindersWithInvalidApps()
  {
    $apps = [
      new Application(),
      new Application()
    ];

    $this->mock->expects($this->exactly(count($apps)))
      ->method('appHasValidPhone')
      ->willReturn(true);
    $this->mock->expects($this->exactly(count($apps)))
      ->method('appHasValidEmail')
      ->willReturn(true);

    $this->mock->expects($this->exactly(count($apps)))
      ->method('appIsValid')
      ->willReturn(false);

    $this->mock->expects($this->exactly(count($apps)))
      ->method('appNeedsSms')
      ->willReturn(true);
    $this->mock->expects($this->exactly(count($apps)))
      ->method('sendSmsReminder');
    $this->mock->expects($this->exactly(count($apps)))
      ->method('appNeedsEmail')
      ->willReturn(true);
    $this->mock->expects($this->exactly(count($apps)))
      ->method('sendEmailReminder');

    $this->invokeMethod($this->mock, 'sendReminders', [$apps]);
  }

  public function testSendEmailRemindersOnly()
  {
    $apps = [
      new Application(),
      new Application()
    ];

    $this->mock->expects($this->exactly(count($apps)))
      ->method('appHasValidPhone')
      ->willReturn(true);
    $this->mock->expects($this->exactly(count($apps)))
      ->method('appHasValidEmail')
      ->willReturn(true);

    $this->mock->expects($this->exactly(count($apps)))
      ->method('appIsValid')
      ->willReturn(false);

    $this->mock->expects($this->exactly(count($apps)))
      ->method('appNeedsSms')
      ->willReturn(false);
    $this->mock->expects($this->never())
      ->method('sendSmsReminder');
    $this->mock->expects($this->exactly(count($apps)))
      ->method('appNeedsEmail')
      ->willReturn(true);
    $this->mock->expects($this->exactly(count($apps)))
      ->method('sendEmailReminder');

    $this->invokeMethod($this->mock, 'sendReminders', [$apps]);
  }

  public function testSendSmsRemindersOnly()
  {
    $apps = [
      new Application(),
      new Application()
    ];

    $this->mock->expects($this->exactly(count($apps)))
      ->method('appHasValidPhone')
      ->willReturn(true);
    $this->mock->expects($this->exactly(count($apps)))
      ->method('appHasValidEmail')
      ->willReturn(true);

    $this->mock->expects($this->exactly(count($apps)))
      ->method('appIsValid')
      ->willReturn(false);

    $this->mock->expects($this->exactly(count($apps)))
      ->method('appNeedsSms')
      ->willReturn(true);
    $this->mock->expects($this->exactly(count($apps)))
      ->method('sendSmsReminder');
    $this->mock->expects($this->exactly(count($apps)))
      ->method('appNeedsEmail')
      ->willReturn(false);
    $this->mock->expects($this->never())
      ->method('sendEmailReminder');

    $this->invokeMethod($this->mock, 'sendReminders', [$apps]);
  }

  public function testAppNeedsSms()
  {
    $app = new Application();
    $app->setCreatedAt((new \DateTime())->modify('+5 minutes'))
      ->setPhone('+37213');

    $mock = $this->createMock(SendFormReminders::class);
    $this->assertTrue($this->invokeMethod($mock, 'appNeedsSms', [$app]));
  }

  public function testAppNeedsSmsNot()
  {
    $app = new Application();
    $app->setCreatedAt((new \DateTime())->modify('+6 minutes'));

    $mock = $this->createMock(SendFormReminders::class);
    $this->assertFalse($this->invokeMethod($mock, 'appNeedsSms', [$app]));
  }

  public function testAppNeedsEmail()
  {
    $app = new Application();
    $app->setCreatedAt((new \DateTime())->modify('+10 minutes'));

    $mock = $this->createMock(SendFormReminders::class);
    $this->assertTrue($this->invokeMethod($mock, 'appNeedsEmail', [$app]));
  }

  public function testAppNeedsEmailNot()
  {
    $app = new Application();
    $app->setCreatedAt((new \DateTime())->modify('+12 minutes'));

    $mock = $this->createMock(SendFormReminders::class);
    $this->assertFalse($this->invokeMethod($mock, 'appNeedsEmail', [$app]));
  }

  public function testAppIsValid()
  {
    $app = new Application();
    $this->validatorMock->method('validate')
      ->willReturn(true);
    $this->altMock->method('getApplicationValidator')
      ->willReturn($this->validatorMock);

    $this->assertTrue($this->invokeMethod($this->altMock, 'appIsValid', [$app]));
  }

  public function testAppIsInvalid()
  {
    $app = new Application();
    $this->validatorMock->method('validate')
      ->willReturn(false);
    $this->altMock->method('getApplicationValidator')
      ->willReturn($this->validatorMock);

    $this->assertFalse($this->invokeMethod($this->altMock, 'appIsValid', [$app]));
  }

  public function testSendEmailReminder()
  {
    $app = new Application();
    $mock = $this->getMockBuilder(SendFormReminders::class)
      ->disableOriginalConstructor()
      ->setMethods(['getMessageTemplateRepository', 'sendReminder', 'getLogger', 'updateApplication'])
      ->getMock();
    $this->templateRepoMock->method('getFormEmailReminderMessage')
      ->willReturn(new Message());

    $mock->method('getLogger')
      ->willReturn($this->loggerMock);
    $mock->method('getMessageTemplateRepository')
      ->willReturn($this->templateRepoMock);
    $mock->method('sendReminder')
      ->willReturn(true);
    $mock->expects($this->once())
      ->method('updateApplication');

    $result = $this->invokeMethod($mock, 'sendEmailReminder', [$app]);
    $this->assertTrue($result);
  }

  public function testSendEmailReminderFails()
  {
    $app = new Application();
    $mock = $this->getMockBuilder(SendFormReminders::class)
      ->disableOriginalConstructor()
      ->setMethods(['getMessageTemplateRepository', 'sendReminder', 'getLogger', 'updateApplication'])
      ->getMock();
    $this->templateRepoMock->method('getFormEmailReminderMessage')
      ->willReturn(new Message());

    $mock->method('getLogger')
      ->willReturn($this->loggerMock);
    $mock->method('getMessageTemplateRepository')
      ->willReturn($this->templateRepoMock);
    $mock->method('sendReminder')
      ->willReturn(false);
    $mock->expects($this->never())
      ->method('updateApplication');

    $result = $this->invokeMethod($mock, 'sendEmailReminder', [$app]);
    $this->assertFalse($result);
  }

  public function testSendSmsReminder()
  {
    $app = new Application();
    $mock = $this->getMockBuilder(SendFormReminders::class)
      ->disableOriginalConstructor()
      ->setMethods(['getMessageTemplateRepository', 'sendReminder', 'getLogger', 'updateApplication'])
      ->getMock();
    $this->templateRepoMock->method('getFormSmsReminderMessage')
      ->willReturn(new Message());

    $mock->method('getLogger')
      ->willReturn($this->loggerMock);
    $mock->method('getMessageTemplateRepository')
      ->willReturn($this->templateRepoMock);
    $mock->method('sendReminder')
      ->willReturn(true);
    $mock->expects($this->once())
      ->method('updateApplication');

    $result = $this->invokeMethod($mock, 'sendSmsReminder', [$app]);
    $this->assertTrue($result);
  }

  public function testSendSmsReminderFails()
  {
    $app = new Application();
    $mock = $this->getMockBuilder(SendFormReminders::class)
      ->disableOriginalConstructor()
      ->setMethods(['getMessageTemplateRepository', 'sendReminder', 'getLogger', 'updateApplication'])
      ->getMock();
    $this->templateRepoMock->method('getFormSmsReminderMessage')
      ->willReturn(new Message());

    $mock->method('getLogger')
      ->willReturn($this->loggerMock);
    $mock->method('getMessageTemplateRepository')
      ->willReturn($this->templateRepoMock);
    $mock->method('sendReminder')
      ->willReturn(false);
    $mock->expects($this->never())
      ->method('updateApplication');

    $result = $this->invokeMethod($mock, 'sendSmsReminder', [$app]);
    $this->assertFalse($result);
  }

  public function testUpdateApplicationWithEmailUpdate()
  {
    $app = new Application();
    $type = Message::MESSAGE_TYPE_EMAIL;

    $this->repositoryMock->expects($this->once())
      ->method('save')
      ->willReturn(true);

    $mock = $this->getMockBuilder(SendFormReminders::class)
      ->disableOriginalConstructor()
      ->setMethods(['getApplicationRepository', 'getLogger'])
      ->getMock();
    $mock->method('getLogger')
      ->willReturn($this->loggerMock);
    $mock->method('getApplicationRepository')
      ->willReturn($this->repositoryMock);

    $result = $this->invokeMethod($mock, 'updateApplication', [$app, $type]);
  }
}
