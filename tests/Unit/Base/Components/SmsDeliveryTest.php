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
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Slim\Container;
use Tests\Helpers\LoggerMockTrait;

class SmsDeliveryTest extends BaseTest
{
  use LoggerMockTrait;

  protected $mock;
  protected $altMock;
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
    $this->altMock = $this->getMockBuilder(SmsDelivery::class)
      ->disableOriginalConstructor()
      ->setMethods(['getSmsSettings', 'getSettings', 'getMessage'])
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
        'senderName' => 'QueenOfEngland',
        'whitelist' => [
          '123123',
          '555555'
        ]
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
      ->setMethods(['setup', 'setMessage', 'getClient', 'handleResult', 'isNumberWhitelisted'])
      ->getMock();

    $mock->expects($this->once())
      ->method('setup')
      ->willReturn(true);
    $mock->expects($this->once())
      ->method('setMessage')
      ->willReturn(true);
    $mock->expects($this->once())
      ->method('isNumberWhitelisted')
      ->willReturn(true);

    $this->clientMock->method('isOk')
      ->willReturn(true);
    $mock->expects($this->atLeastOnce())
      ->method('getClient')
      ->willReturn($this->clientMock);
    $mock->expects($this->once())
      ->method('handleResult');

    $mock->send(new Message());
  }

  public function testGetSettings()
  {
    $this->containerMock->method('get')
      ->willReturn($this->settings);
    $this->mock->expects($this->once())
      ->method('getContainer')
      ->willReturn($this->containerMock);

    $result = $this->invokeMethod($this->mock, 'getSmsSettings', []);
    $this->assertArrayHasKey('apiUrl', $result);
  }

  public function testSetup()
  {
    $mock = $this->getMockBuilder(SmsDelivery::class)
      ->disableOriginalConstructor()
      ->setMethods(['getSmsSettings', 'getMessage', 'getClient'])
      ->getMock();

    $mock->expects($this->once())
      ->method('getSmsSettings')
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

  public function testHandleResult()
  {
    $resultMock = 'OK fuckyeahhh!!!';
    $mock = $this->getMockBuilder(SmsDelivery::class)
      ->disableOriginalConstructor()
      ->setMethods(['resolveErrorCode', 'getClient', 'getLogger'])
      ->getMock();
    $mock->method('getLogger')
      ->willReturn($this->loggerMock);

    $mock->expects($this->never())
      ->method('resolveErrorCode')
      ->willReturn(false);
    $mock->method('getClient')
      ->willReturn($this->clientMock);

    $this->invokeMethod($mock, 'handleResult', [$resultMock]);
  }

  public function testHandleResultWithError()
  {
    $resultMock = 'ERROR fuckyeahhh!!!';
    $mock = $this->getMockBuilder(SmsDelivery::class)
      ->disableOriginalConstructor()
      ->setMethods(['resolveErrorCode', 'getClient', 'getLogger'])
      ->getMock();
    $mock->method('getLogger')
      ->willReturn($this->loggerMock);

    $mock->expects($this->once())
      ->method('resolveErrorCode')
      ->willReturn(false);
    $mock->method('getClient')
      ->willReturn($this->clientMock);

    $this->invokeMethod($mock, 'handleResult', [$resultMock]);
  }

  public function testResolveErrorMessage()
  {
    $knownErrors = [101,102,209];

    foreach ($knownErrors as $code)
    {
      $this->assertInternalType('string', $this->invokeMethod($this->mock, 'resolveErrorCode', [$code]));
    }
  }

  public function testResolveUnknownErrorCode()
  {
    $this->assertSame('Unknown error!', $this->invokeMethod($this->mock, 'resolveErrorCode', [1337]));
  }

  public function testIsNumberWhitelistedWithUnsetWhitelistOnTestserver()
  {
    $settings = $this->settings;
    unset($settings['whitelist']);

    $this->altMock->method('getSmsSettings')
      ->willReturn($settings);
    $this->altMock->method('getSettings')
      ->willReturn(['broker' => [
        'environment' => 'testserver'
      ]]);

    $this->assertFalse($this->invokeMethod($this->altMock, 'isNumberWhiteListed'));
  }

  public function testIsNumberWhitelistedWithEmptyWhitelistOnTestServer()
  {
    $settings = $this->settings;
    $settings['whitelist'] = [];

    $this->altMock->method('getSmsSettings')
      ->willReturn($settings);
    $this->altMock->method('getSettings')
      ->willReturn(['broker' => [
        'environment' => 'testserver'
      ]]);

    $this->assertFalse($this->invokeMethod($this->altMock, 'isNumberWhiteListed'));
  }

  public function testIsNumberWhitelistedOnTestserverWithSuccess()
  {
    $settings = $this->settings;
    $settings['whitelist'] = ['123123'];

    $this->altMock->method('getSmsSettings')
      ->willReturn($settings);
    $this->altMock->method('getSettings')
      ->willReturn(['broker' => [
        'environment' => 'testserver'
      ]]);
    $this->altMock->method('getMessage')
      ->willReturn((new Message())->setRecipient('123123'));

    $this->assertTrue($this->invokeMethod($this->altMock, 'isNumberWhiteListed'));
  }

  public function testIsNumberWhitelistedOnTestserverWithoutSuccess()
  {
    $settings = $this->settings;
    $settings['whitelist'] = ['123123'];

    $this->altMock->method('getSmsSettings')
      ->willReturn($settings);
    $this->altMock->method('getSettings')
      ->willReturn(['broker' => [
        'environment' => 'testserver'
      ]]);
    $this->altMock->method('getMessage')
      ->willReturn((new Message())->setRecipient('123'));

    $this->assertFalse($this->invokeMethod($this->altMock, 'isNumberWhiteListed'));
  }

  public function testIsNumberWhitelistedOnProduction()
  {
    $settings = $this->settings;
    $settings['whitelist'] = ['123123'];

    $this->altMock->method('getSmsSettings')
      ->willReturn($settings);
    $this->altMock->method('getSettings')
      ->willReturn(['broker' => [
        'environment' => 'production'
      ]]);

    $this->assertTrue($this->invokeMethod($this->altMock, 'isNumberWhiteListed'));
  }
}
