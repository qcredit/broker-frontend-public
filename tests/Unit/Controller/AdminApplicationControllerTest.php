<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 06.04.18
 * Time: 17:04
 */

namespace Tests\Unit\Controller;

use App\Controller\Admin\AdminApplicationController;
use Broker\Domain\Entity\Application;
use Broker\Persistence\Doctrine\ApplicationRepository;
use Broker\Persistence\Doctrine\OfferRepository;
use Broker\System\BaseTest;
use Slim\Container;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class AdminApplicationControllerTest extends BaseTest
{
  protected $mock;
  protected $requestMock;
  protected $responseMock;
  protected $containerMock;
  protected $repositoryMock;
  protected $apps;

  public function setUp()
  {
    $this->mock = $this->getMockBuilder(AdminApplicationController::class)
      ->disableOriginalConstructor()
      ->setMethods(['getAppRepository', 'getContainer', 'getOfferRepository', 'render'])
      ->getMock();

    $this->repositoryMock = $this->getMockBuilder(ApplicationRepository::class)
      ->disableOriginalConstructor()
      ->setMethods(['getAll', 'getOneBy'])
      ->getMock();

    $this->requestMock = $this->createMock(Request::class);
    $this->responseMock = $this->createMock(Response::class);

    $this->mock->method('render')->willReturnArgument(2);

    $this->apps = [
      (new Application())->setFirstName('Slava')->setLastName('Bogu')
    ];
  }

  public function testIndexAction()
  {
    $mock = $this->mock;

    $this->repositoryMock->method('getAll')
      ->willReturn($this->apps);

    $mock->expects($this->once())
      ->method('getAppRepository')
      ->willReturn($this->repositoryMock);

    $result = $mock->indexAction($this->requestMock, $this->responseMock, []);

    $this->assertTrue(is_array($result));
    $this->assertInstanceOf(Application::class, $result['applications'][0]);

  }

  public function testViewAction()
  {
    $this->repositoryMock->method('getOneBy')
      ->willReturn(new Application());

    $this->mock->expects($this->once())
      ->method('getAppRepository')
      ->willReturn($this->repositoryMock);

    $result = $this->mock->viewAction($this->requestMock, $this->responseMock, []);

    $this->assertTrue(is_array($result));
    $this->assertInstanceOf(Application::class, $result['application']);
  }

  public function testFindEntity()
  {
    $this->repositoryMock->method('getOneBy')
      ->willReturn(new Application());

    $this->mock->expects($this->once())
      ->method('getAppRepository')
      ->willReturn($this->repositoryMock);

    $this->assertInstanceOf(Application::class, $this->invokeMethod($this->mock, 'findEntity', [1, $this->requestMock, $this->responseMock]));
  }

  public function testFindNoEntity()
  {
    $this->repositoryMock->method('getOneBy')
      ->willReturn(null);

    $this->mock->expects($this->once())
      ->method('getAppRepository')
      ->willReturn($this->repositoryMock);

    $this->expectException(NotFoundException::class);
    $this->invokeMethod($this->mock, 'findEntity', [1, $this->requestMock, $this->responseMock]);
  }
}
