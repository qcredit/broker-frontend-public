<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 20.04.18
 * Time: 09:52
 */

namespace Tests\Unit\Base\Components;

use App\Base\Components\ApiDelivery;
use App\Base\Components\HttpClient;
use Broker\Domain\Entity\Message;
use Broker\Domain\Entity\PartnerRequest;
use Broker\Domain\Entity\PartnerResponse;
use Broker\System\BaseTest;
use Broker\System\Delivery\DeliveryOptions;
use Broker\System\Delivery\DeliveryHeaders;
use Slim\Container;
use Tests\Helpers\LoggerMockTrait;

class ApiDeliveryTest extends BaseTest
{
  use LoggerMockTrait;

  protected $mock;
  protected $messageMock;
  protected $requestMock;
  protected $responseMock;
  protected $containerMock;
  protected $clientMock;

  public function setUp()
  {
    $this->containerMock = $this->createMock(Container::class);
    $this->mock = $this->getMockBuilder(ApiDelivery::class)
      ->disableOriginalConstructor()
      ->setMethods(['getMessage', 'setClientOption', 'getClient'])
      ->getMock();

    $this->requestMock = $this->getMockBuilder(PartnerRequest::class)
      ->setMethods(['getRequestPayload', 'getType', 'getPartner', 'getOffer'])
      ->getMock();
    $this->responseMock = $this->getMockBuilder(PartnerResponse::class)
      ->setMethods(['setOk'])
      ->getMock();

    $this->messageMock = $this->getMockBuilder(Message::class)
      ->getMock();
    $this->messageMock->method('getRelatedEntity')
      ->willReturn($this->requestMock);

    $this->clientMock = $this->createMock(HttpClient::class);
  }

  public function test__construct()
  {
    $instance = new ApiDelivery($this->containerMock);

    $this->assertInstanceOf(HttpClient::class, $instance->getClient());
    $this->assertInstanceOf(Container::class, $instance->getContainer());
    $this->assertNotNull($instance->getResponse());
  }

  public function testSetup()
  {
    $mock = $this->getMockBuilder(ApiDelivery::class)
      ->disableOriginalConstructor()
      ->setMethods(['setClientHeaders', 'getMessage', 'setClientOptions', 'getResponse'])
      ->getMock();
    $mock->expects($this->once())
      ->method('setClientOptions')
      ->willReturnSelf();
    $mock->expects($this->once())
      ->method('setClientHeaders');
    $mock->expects($this->once())
      ->method('getResponse')
      ->willReturn($this->responseMock);
    $mock->expects($this->once())
      ->method('getMessage')
      ->willReturn($this->messageMock);

    $this->invokeMethod($mock, 'setup', []);
  }

  public function testSetClientOptionsWithNoOptions()
  {
    $messageMock = $this->getMockBuilder(Message::class)
      ->setMethods(['getDeliveryMethod', 'hasDeliveryOptions'])
      ->getMock();

    $messageMock->method('hasDeliveryOptions')
      ->willReturn(false);

    $messageMock->expects($this->never())
      ->method('getDeliveryMethod');

    $this->mock->method('getMessage')
      ->willReturn($messageMock);

    $this->invokeMethod($this->mock, 'setClientOptions', []);
  }

  public function testSetClientOptions()
  {
    $options = ['one','two','three'];
    $this->messageMock->method('hasDeliveryOptions')
      ->willReturn(true);
    $this->messageMock->method('getDeliveryOptions')
      ->willReturn((new DeliveryOptions())->setOptions($options));
    $this->mock->method('getMessage')
      ->willReturn($this->messageMock);

    $this->mock->expects($this->exactly(count($options)))
      ->method('getClient')
      ->willReturn($this->clientMock);

    $this->invokeMethod($this->mock, 'setClientOptions', []);
  }

  public function testSetClientHeaders()
  {
    $headers = ['one','two','three','four'];
    $this->messageMock->method('hasDeliveryHeaders')
      ->willReturn(true);
    $this->messageMock->method('getDeliveryHeaders')
      ->willReturn((new DeliveryHeaders())->setHeaders($headers));
    $this->mock->method('getMessage')
      ->willReturn($this->messageMock);

    $this->mock->expects($this->once())
      ->method('getClient')
      ->willReturn($this->clientMock);

    $this->invokeMethod($this->mock, 'setClientHeaders', []);
  }

  public function testSend()
  {
    $mock = $this->getMockBuilder(ApiDelivery::class)
      ->disableOriginalConstructor()
      ->setMethods(['setup', 'getClient', 'getLogger', 'getResponse'])
      ->getMock();

    $this->clientMock->method('getStatusCode')
      ->willReturn(500);
    $this->clientMock->method('send')
      ->willReturn('');
    $mock->method('getClient')
      ->willReturn($this->clientMock);
    $mock->method('getResponse')
      ->willReturn($this->responseMock);
    $mock->method('getLogger')
      ->willReturn($this->loggerMock);

    $mock->send($this->messageMock);
    $this->assertFalse($mock->isOk());
  }

  public function testSendWithSuccessCall()
  {
    $mock = $this->getMockBuilder(ApiDelivery::class)
      ->disableOriginalConstructor()
      ->setMethods(['setup', 'getClient', 'getLogger', 'getResponse'])
      ->getMock();

    $this->clientMock->method('getStatusCode')
      ->willReturn(210);
    $this->clientMock->method('send')
      ->willReturn('');
    $mock->method('getClient')
      ->willReturn($this->clientMock);
    $mock->method('getResponse')
      ->willReturn($this->responseMock);
    $this->loggerMock->expects($this->never())
      ->method('critical');
    $mock->method('getLogger')
      ->willReturn($this->loggerMock);

    $mock->send($this->messageMock);
    $this->assertTrue($mock->isOk());
  }

  public function testSendWithExceptionThrown()
  {
    $mock = $this->getMockBuilder(ApiDelivery::class)
      ->disableOriginalConstructor()
      ->setMethods(['setup', 'getClient', 'getLogger', 'getResponse'])
      ->getMock();

    $this->clientMock->method('send')
      ->willThrowException(new \Exception());
    $mock->method('getClient')
      ->willReturn($this->clientMock);
    $this->loggerMock->expects($this->once())
      ->method('critical');
    $mock->method('getLogger')
      ->willReturn($this->loggerMock);
    $mock->method('getResponse')
      ->willReturn($this->responseMock);

    $mock->send($this->messageMock);
    $this->assertFalse($mock->isOk());
  }
}
