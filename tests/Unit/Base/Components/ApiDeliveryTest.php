<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 20.04.18
 * Time: 09:52
 */

namespace Tests\Unit\Base\Components;

use App\Base\Components\ApiDelivery;
use Broker\Domain\Entity\Message;
use Broker\Domain\Entity\PartnerRequest;
use Broker\Domain\Entity\PartnerResponse;
use Broker\System\BaseTest;
use Broker\Domain\Entity\Partner;
use Broker\Domain\Entity\Offer;

class ApiDeliveryTest extends BaseTest
{
  protected $mock;
  protected $messageMock;
  protected $requestMock;
  protected $responseMock;

  public function setUp()
  {
    $this->mock = $this->getMockBuilder(ApiDelivery::class)
      ->disableOriginalConstructor()
      ->setMethods(['getMessage', 'setClientOption', 'setClientHeaders'])
      ->getMock();

    $this->messageMock = (new Message())->setRelatedEntity((new PartnerRequest())->setPartner(new Partner()));
    $this->requestMock = $this->getMockBuilder(PartnerRequest::class)
      ->setMethods(['getRequestPayload', 'getType', 'getPartner', 'getOffer'])
      ->getMock();
    $this->responseMock = $this->getMockBuilder(PartnerResponse::class)
      ->setMethods(['setOk'])
      ->getMock();
  }

  public function test__construct()
  {
    $instance = new ApiDelivery();

    $this->assertInternalType('resource', $instance->getClient());
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
      ->method('getResponse')
      ->willReturn($this->responseMock);
    $mock->expects($this->once())
      ->method('getMessage')
      ->willReturn($this->messageMock);

    $this->invokeMethod($mock, 'setup', []);
  }

  public function testSetClientOptions()
  {
    $this->requestMock->method('getType')
      ->willReturn(PartnerRequest::REQUEST_TYPE_INITIAL);
    $this->requestMock->method('getRequestPayload')
      ->willReturn([]);
    $this->requestMock->expects($this->once())
      ->method('getPartner')
      ->willReturn((new Partner())->setApiTestUrl('google.ee'));
    $this->messageMock->setRelatedEntity($this->requestMock);
    $this->mock->expects($this->atLeastOnce())
      ->method('getMessage')
      ->willReturn($this->messageMock);

    $this->mock->expects($this->at(1))
      ->method('setClientOption')
      ->with($this->equalTo(CURLOPT_URL), $this->equalTo('google.ee'));
    $this->mock->expects($this->at(2))
      ->method('setClientOption')
      ->with($this->equalTo(CURLOPT_POSTFIELDS), $this->equalTo([]));
    $this->mock->expects($this->once())
      ->method('setClientHeaders');

    $this->invokeMethod($this->mock, 'setClientOptions', []);
  }

  public function testSetClientOptionsOnUpdate()
  {
    $this->requestMock->method('getType')
      ->willReturn(PartnerRequest::REQUEST_TYPE_UPDATE);
    $this->requestMock->method('getRequestPayload')
      ->willReturn([]);
    $this->requestMock->expects($this->once())
      ->method('getPartner')
      ->willReturn((new Partner())->setApiTestUrl('google.ee'));
    $this->requestMock->method('getOffer')
      ->willReturn((new Offer())->setRemoteId('porno'));
    $this->messageMock->setRelatedEntity($this->requestMock);
    $this->mock->expects($this->once())
      ->method('getMessage')
      ->willReturn($this->messageMock);

    $this->mock->expects($this->at(1))
      ->method('setClientOption')
      ->with($this->equalTo(CURLOPT_URL), $this->equalTo('google.ee/porno'));
    $this->mock->expects($this->once())
      ->method('setClientHeaders');

    $this->invokeMethod($this->mock, 'setClientOptions', []);
  }

  public function testSetClientOptionsOnChoose()
  {
    $this->requestMock->method('getType')
      ->willReturn(PartnerRequest::REQUEST_TYPE_CHOOSE);
    $this->requestMock->method('getRequestPayload')
      ->willReturn([]);
    $this->requestMock->expects($this->once())
      ->method('getPartner')
      ->willReturn((new Partner())->setApiTestUrl('google.ee'));
    $this->requestMock->method('getOffer')
      ->willReturn((new Offer())->setRemoteId('porno'));
    $this->messageMock->setRelatedEntity($this->requestMock);
    $this->mock->expects($this->once())
      ->method('getMessage')
      ->willReturn($this->messageMock);

    $this->mock->expects($this->at(1))
      ->method('setClientOption')
      ->with($this->equalTo(CURLOPT_URL), $this->equalTo('google.ee/porno'));
    $this->mock->expects($this->at(2))
      ->method('setClientOption')
      ->with($this->equalTo(CURLOPT_POSTFIELDS), $this->equalTo([]));
    $this->mock->expects($this->at(3))
      ->method('setClientOption')
      ->with($this->equalTo(CURLOPT_CUSTOMREQUEST), $this->equalTo('PATCH'));
    $this->mock->expects($this->once())
      ->method('setClientHeaders');
    $this->mock->setResponse($this->responseMock);

    $this->mock->expects($this->at(4))
      ->method('setClientOption')
      ->with($this->equalTo(CURLOPT_RETURNTRANSFER), $this->equalTo(true));

    $this->invokeMethod($this->mock, 'setClientOptions', []);
  }

  public function testSend()
  {
    $mock = $this->getMockBuilder(ApiDelivery::class)
      ->disableOriginalConstructor()
      ->setMethods(['setup', 'makeCall'])
      ->getMock();

    $mock->expects($this->once())
      ->method('setup');
    $mock->expects($this->once())
      ->method('makeCall');

    $mock->send($this->messageMock);
  }

  public function testMakeCallWithException()
  {
    $mock = $this->getMockBuilder(ApiDelivery::class)
      ->disableOriginalConstructor()
      ->setMethods(['getClient', 'getResponse'])
      ->getMock();

    $mock->method('getClient')
      ->willThrowException(new \Exception('oh wtf'));
    $this->responseMock->expects($this->once())
      ->method('setOk')
      ->with($this->equalTo(false));
    $mock->method('getResponse')
      ->willReturn($this->responseMock);

    $this->invokeMethod($mock, 'makeCall', []);
  }
}
