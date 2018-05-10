<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 09.05.18
 * Time: 14:30
 */

namespace Tests\Unit\Controller;

use App\Controller\ApiController;
use Broker\Domain\Entity\PartnerResponse;
use Broker\Domain\Factory\PartnerResponseFactory;
use Broker\Domain\Interfaces\Service\PartnerUpdateServiceInterface;
use Broker\Domain\Service\PartnerUpdateService;
use Broker\System\BaseTest;
use Monolog\Logger;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Broker\Domain\Entity\Partner;

class ApiControllerTest extends BaseTest
{
  protected $containerMock;
  protected $partnerUpdateServiceMock;
  protected $loggerMock;
  protected $requestMock;
  protected $responseMock;

  /**
   * @throws \ReflectionException
   */
  public function setUp()
  {
    $this->containerMock = $this->getMockBuilder(Container::class)
      ->disableOriginalConstructor()
      ->setMethods(['get'])
      ->getMock();
    $this->partnerUpdateServiceMock = $this->getMockBuilder(PartnerUpdateService::class)
      ->disableOriginalConstructor()
      ->setMethods(['run', 'getResponseForPartner'])
      ->getMock();
    $this->loggerMock = $this->createMock(Logger::class);
    $this->requestMock = $this->createMock(Request::class);
    $this->responseMock = $this->createMock(Response::class);
  }

  public function test__construct()
  {
    $mock = $this->getMockBuilder(PartnerUpdateServiceInterface::class)
      ->getMockForAbstractClass();

    $instance = new ApiController($mock, $this->containerMock);
    $this->assertInstanceOf(PartnerUpdateServiceInterface::class, $instance->getPartnerUpdateService());
    $this->assertInstanceOf(Container::class, $instance->getContainer());
  }

  public function testUpdateAction()
  {
    $data = [
      'id' => 34234
    ];
    $responseForPartner = [
      'message' => 'OK'
    ];
    $mock = $this->getMockBuilder(ApiController::class)
      ->disableOriginalConstructor()
      ->setMethods(['getPartnerUpdateService', 'getLogger', 'preparePartnerResponse'])
      ->getMock();

    $this->requestMock->method('getParsedBody')
      ->willReturn($data);
    $this->requestMock->method('getAttribute')
      ->willReturn(new Partner());
    $mock->expects($this->once())
      ->method('preparePartnerResponse')
      ->willReturn(new PartnerResponse());

    $this->partnerUpdateServiceMock->method('getResponseForPartner')
      ->willReturn($responseForPartner);
    $mock->method('getPartnerUpdateService')
      ->willReturn($this->partnerUpdateServiceMock);
    $mock->method('getLogger')
      ->willReturn($this->loggerMock);

    $this->responseMock->method('withJson')
      ->with($this->equalTo($responseForPartner));

    $mock->updateAction($this->requestMock, $this->responseMock, []);
  }

  public function testPreparePartnerResponse()
  {
    $data = [];
    $mock = $this->getMockBuilder(ApiController::class)
      ->disableOriginalConstructor()
      ->setMethods(['getPartnerResponseFactory'])
      ->getMock();

    $factoryMock = $this->createMock(PartnerResponseFactory::class, ['create']);
    $factoryMock->method('create')
      ->willReturn(new PartnerResponse());

    $mock->method('getPartnerResponseFactory')
      ->willReturn($factoryMock);

    $result = $this->invokeMethod($mock, 'preparePartnerResponse', [$data, new Partner()]);
    $this->assertInstanceOf(PartnerResponse::class, $result);
  }
}
