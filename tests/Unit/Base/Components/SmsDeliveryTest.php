<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 30.04.18
 * Time: 09:28
 */

namespace Tests\Unit\Base\Components;

use App\Base\Components\HttpClient;
use App\Base\Components\SmsDelivery;
use Broker\Domain\Entity\Message;
use Broker\System\BaseTest;
use PHPUnit\Framework\TestCase;
use Slim\Container;

class SmsDeliveryTest extends BaseTest
{
  protected $mock;
  protected $clientMock;
  protected $containerMock;
  protected $settings;

  public function setUp()/* The :void return type declaration that should be here would cause a BC issue */
  {
    parent::setUp();

    $this->mock = $this->getMockBuilder(SmsDelivery::class)
      ->disableOriginalConstructor()
      ->setMethods(['getContainer'])
      ->getMock();
    $this->clientMock = $this->getMockBuilder(HttpClient::class)
      ->setMethods(['isOk', 'setBaseUrl'])
      ->getMock();
    $this->containerMock = $this->createMock(Container::class);
    $this->settings = [
      'messente' => [
        'apiUrl' => 'www.ee',
        'username' => 'chicken',
        'password' => 'umbrella',
        'senderName' => 'QueenOfEngland'
      ]
    ];
  }

  public function test__construct()
  {
    $containerMock = $this->createMock(Container::class);
    $instance = new SmsDelivery($containerMock);
    $this->assertInstanceOf(HttpClient::class, $instance->getClient());
  }

  public function testSend()
  {
    $mock = $this->getMockBuilder(SmsDelivery::class)
      ->disableOriginalConstructor()
      ->setMethods(['setup', 'setMessage', 'getClient'])
      ->getMock();

    $mock->expects($this->once())
      ->method('setup')
      ->willReturn(true);
    $mock->expects($this->once())
      ->method('setMessage')
      ->willReturn(true);

    $this->clientMock->method('isOk')
      ->willReturn(true);
    $mock->expects($this->atLeastOnce())
      ->method('getClient')
      ->willReturn($this->clientMock);

    $mock->send(new Message());
  }

  public function testGetSettings()
  {
    $this->containerMock->method('get')
      ->willReturn($this->settings);
    $this->mock->expects($this->once())
      ->method('getContainer')
      ->willReturn($this->containerMock);

    $result = $this->invokeMethod($this->mock, 'getSettings', []);
    $this->assertArrayHasKey('apiUrl', $result);
  }

  public function testSetup()
  {
    $mock = $this->getMockBuilder(SmsDelivery::class)
      ->disableOriginalConstructor()
      ->setMethods(['getSettings', 'getMessage', 'getClient'])
      ->getMock();

    $mock->expects($this->once())
      ->method('getSettings')
      ->willReturn($this->settings);
    $mock->expects($this->exactly(2))
      ->method('getMessage')
      ->willReturn(new Message());
    $this->clientMock->expects($this->once())
      ->method('setBaseUrl');
    $mock->expects($this->atLeastOnce())
      ->method('getClient')
      ->willReturn($this->clientMock);

    $result = $this->invokeMethod($mock, 'setup', []);
  }
}
