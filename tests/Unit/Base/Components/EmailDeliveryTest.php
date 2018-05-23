<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 18.04.18
 * Time: 13:50
 */

namespace Tests\Unit\Base\Components;

use App\Base\Components\EmailDelivery;
use Broker\Domain\Entity\Message;
use Broker\System\BaseTest;
use Broker\System\Error\InvalidConfigException;
use Broker\System\Traits\LoggerTrait;
use Monolog\Logger;
use PHPMailer\PHPMailer\PHPMailer;
use Slim\Container;
use Tests\Helpers\LoggerMockTrait;

class EmailDeliveryTest extends BaseTest
{
  use LoggerMockTrait;

  protected $mock;
  protected $containerMock;
  protected $clientMock;
  protected $settings;

  public function setUp()
  {
    $this->mock = $this->getMockBuilder(EmailDelivery::class)
      ->disableOriginalConstructor()
      ->setMethods(['getContainer', 'getLogger'])
      ->getMock();
    $this->mock->method('getLogger')
      ->willReturn($this->loggerMock);
    $this->containerMock = $this->createMock(Container::class);
    $this->clientMock = $this->createMock(PHPMailer::class);

    $this->settings = [
      'sender' => 'info@pornhub.com',
      'senderName' => 'Hugh Hefner',
      'host' => 'smtp.porhub.com',
      'username' => 'littlepervert123',
      'password' => '1337',
      'secure' => 'tls',
      'port' => 587
    ];
  }

  public function test__construct()
  {
    $containerMock = $this->createMock(Container::class);
    $instance = new EmailDelivery($containerMock);
    $this->assertInstanceOf(PHPMailer::class, $instance->getClient());
  }

  public function testGetSettings()
  {
    $settings = [
      'mailer' => [
        'host' => 'pornhub.com',
        'password' => '1337'
      ]
    ];

    $this->containerMock->expects($this->once())
      ->method('get')
      ->willReturn($settings);
    $this->mock->expects($this->once())
      ->method('getContainer')
      ->willReturn($this->containerMock);

    $result = $this->invokeMethod($this->mock, 'getSettings', []);

    $this->assertTrue(is_array($result));
    $this->assertSame($settings['mailer'], $result);
  }

  public function testGetSettingsNotSet()
  {
    $settings = [
    ];

    $this->containerMock->expects($this->once())
      ->method('get')
      ->willReturn($settings);
    $this->mock->expects($this->once())
      ->method('getContainer')
      ->willReturn($this->containerMock);

    $this->expectException(InvalidConfigException::class);

    $this->invokeMethod($this->mock, 'getSettings', []);
  }

  public function testSetupClient()
  {
    $mock = $this->getMockBuilder(EmailDelivery::class)
      ->disableOriginalConstructor()
      ->setMethods(['getSettings', 'getClient'])
      ->getMock();

    $mock->expects($this->once())
      ->method('getSettings')
      ->willReturn($this->settings);
    $mock->method('getClient')
      ->willReturn($this->clientMock);

    $this->invokeMethod($mock, 'setupClient', []);

    $this->assertSame($this->settings['host'], $mock->getClient()->Host);
    $this->assertSame($this->settings['username'], $mock->getClient()->Username);
    $this->assertSame($this->settings['password'], $mock->getClient()->Password);
    $this->assertSame($this->settings['port'], $mock->getClient()->Port);
    $this->assertSame($this->settings['secure'], $mock->getClient()->SMTPSecure);
  }

  public function testSetupMessage()
  {
    $mock = $this->getMockBuilder(EmailDelivery::class)
      ->disableOriginalConstructor()
      ->setMethods(['getSettings', 'getClient', 'getMessage'])
      ->getMock();

    $message = (new Message())->setTitle('hello')
      ->setBody('dis is special letter for you, my friend!')
      ->setRecipient('kersti@president.ee');

    $mock->expects($this->once())
      ->method('getSettings')
      ->willReturn($this->settings);
    $mock->method('getClient')
      ->willReturn($this->clientMock);
    $mock->expects($this->once())
      ->method('getMessage')
      ->willReturn($message);

    $this->invokeMethod($mock, 'setupMessage', []);

    $this->assertSame($message->getTitle(), $mock->getClient()->Subject);
    $this->assertSame($message->getBody(), $mock->getClient()->Body);
    $this->assertSame($this->settings['sender'], $mock->getClient()->From);

  }

  public function testSend()
  {
    $mock = $this->getMockBuilder(EmailDelivery::class)
      ->disableOriginalConstructor()
      ->setMethods(['setupClient', 'setupMessage', 'getClient'])
      ->getMock();

    $mock->expects($this->once())
      ->method('setupClient')
      ->willReturnSelf();
    $mock->expects($this->once())
      ->method('setupMessage')
      ->willReturnSelf();
    $mock->expects($this->once())
      ->method('getClient')
      ->willReturn($this->clientMock);

    $message = new Message();

    $mock->setContainer($this->containerMock);
    $mock->send($message);

    $this->assertTrue($mock->isOk());
  }

  public function testSendFails()
  {
    $mock = $this->getMockBuilder(EmailDelivery::class)
      ->disableOriginalConstructor()
      ->setMethods(['setupClient', 'setupMessage', 'getClient', 'getContainer'])
      ->getMock();

    $mock->expects($this->once())
      ->method('setupClient')
      ->willReturnSelf();
    $mock->expects($this->once())
      ->method('setupMessage')
      ->willReturnSelf();
    $this->clientMock->method('send')
      ->willThrowException(new \Exception());
    $mock->expects($this->exactly(2))
      ->method('getClient')
      ->willReturn($this->clientMock);
    $this->containerMock->method('get')
      ->willReturn($this->createMock(Logger::class));
    $mock->expects($this->once())
      ->method('getContainer')
      ->willReturn($this->containerMock);

    $message = new Message();

    $mock->setContainer($this->containerMock);
    $mock->send($message);

    $this->assertFalse($mock->isOk());
  }
}
