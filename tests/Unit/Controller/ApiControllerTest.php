<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 09.05.18
 * Time: 14:30
 */

namespace Tests\Unit\Controller;

use App\Controller\ApiController;
use Broker\Domain\Interfaces\Service\PartnerUpdateServiceInterface;
use Broker\Domain\Service\PartnerUpdateService;
use Broker\System\BaseTest;
use Monolog\Logger;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

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

  public function testAasaUpdateAction()
  {
    $mock = $this->getMockBuilder(ApiController::class)
      ->disableOriginalConstructor()
      ->setMethods(['getPartnerUpdateService', 'getLogger'])
      ->getMock();

    $this->requestMock->method('getParsedBody')
  }
}
