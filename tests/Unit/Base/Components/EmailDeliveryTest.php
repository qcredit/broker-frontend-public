<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 18.04.18
 * Time: 13:50
 */

namespace Tests\Unit\Base\Components;

use App\Base\Components\EmailDelivery;
use App\Base\Components\HttpClient;
use Broker\Domain\Entity\Message;
use Broker\System\BaseTest;
use Broker\System\Error\InvalidConfigException;
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
    $this->setupMocks();
    $this->mock = $this->getMockBuilder(EmailDelivery::class)
      ->disableOriginalConstructor()
      ->setMethods(['getContainer', 'getLogger'])
      ->getMock();
    $this->mock->method('getLogger')
      ->willReturn($this->loggerMock);
    $this->containerMock = $this->createMock(Container::class);
    $this->clientMock = $this->createMock(HttpClient::class);

    $this->settings = [
      'sender' => 'info@pornhub.com',
      'senderName' => 'Hugh Hefner',
      'host' => 'smtp.porhub.com',
      'username' => 'littlepervert123',
      'password' => '1337',
      'secure' => 'tls',
      'port' => 587,
      'apiUrl' => 'someSendGridURL'
    ];
  }

  public function test__construct()
  {
    $containerMock = $this->createMock(Container::class);
    $instance = new EmailDelivery($containerMock);
    $this->assertInstanceOf(HttpClient::class, $instance->getClient());
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

    $this->assertSame($this->settings['apiKey'], $mock->getClient()->getBaseUrl());
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
    $this->clientMock->method('setClientOption')
      ->with($this->equalTo(CURLOPT_POSTFIELDS));
    $mock->method('getClient')
      ->willReturn($this->clientMock);

    $this->invokeMethod($mock, 'setupMessage', []);
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
      ->setMethods(['setupClient', 'setupMessage', 'getClient', 'getContainer', 'getLogger'])
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
    $mock->method('getLogger')
      ->willReturn($this->loggerMock);

    $message = new Message();

    $mock->setContainer($this->containerMock);
    $mock->send($message);

    $this->assertFalse($mock->isOk());
  }
}
