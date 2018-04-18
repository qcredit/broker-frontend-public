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
use PHPMailer\PHPMailer\PHPMailer;
use Slim\Container;

class EmailDeliveryTest extends BaseTest
{
  protected $mock;
  protected $containerMock;
  protected $clientMock;
  protected $settings;

  public function setUp()
  {
    $this->mock = $this->getMockBuilder(EmailDelivery::class)
      ->disableOriginalConstructor()
      ->setMethods(['getContainer'])
      ->getMock();
    $this->containerMock = $this->createMock(Container::class);
    $this->clientMock = $this->createMock(PHPMailer::class);

    $this->settings = [
      'sender' => 'info@pornhub.com',
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

    $this->assertSame($settings['host'], $mock->getClient()->Host);
    $this->assertSame($settings['username'], $mock->getClient()->Username);
    $this->assertSame($settings['password'], $mock->getClient()->Password);
    $this->assertSame($settings['port'], $mock->getClient()->Port);
    $this->assertSame($settings['secure'], $mock->getClient()->SMTPSecure);
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

  }

  public function testIsOk()
  {

  }
}
