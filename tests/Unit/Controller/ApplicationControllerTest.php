<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 09.04.18
 * Time: 11:40
 */

namespace Tests\Unit\Controller;

use App\controllers\ApplicationController;
use Broker\System\BaseTest;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Container;
use Slim\Views\Twig;
use Broker\Persistence\Doctrine\ApplicationRepository;
use Broker\Domain\Entity\Application;

class ApplicationControllerTest extends BaseTest
{
  protected $mock;
  protected $requestMock;
  protected $responseMock;
  protected $containerMock;
  protected $repositoryMock;

  public function setUp()
  {
    $this->mock = $this->getMockBuilder(ApplicationController::class)
      ->disableOriginalConstructor()
      ->setMethods(['getAppRepository', 'getContainer'])
      ->getMock();
    $this->requestMock = $this->createMock(Request::class);
    $this->responseMock = $this->createMock(Response::class);
    $this->containerMock = $this->getMockBuilder(Container::class)
      ->setMethods(['get'])
      ->getMock();
    $this->repositoryMock = $this->getMockBuilder(ApplicationRepository::class)
      ->disableOriginalConstructor()
      ->setMethods(['getAll', 'getOneBy', 'getByHash'])
      ->getMock();

    $twigMock = $this->getMockBuilder(Twig::class)
      ->disableOriginalConstructor()
      ->getMock();
    $twigMock->method('render')
      ->willReturn(new Response());

    $this->containerMock->method('get')
      ->willReturn($twigMock);

    $this->mock->method('getContainer')->willReturn($this->containerMock);
  }

  public function testOfferListAction()
  {
    $mock = $this->mock;

    $this->repositoryMock->expects($this->once())
      ->method('getByHash')
      ->willReturn(new Application());

    $mock->expects($this->once())
      ->method('getAppRepository')
      ->willReturn($this->repositoryMock);

    $this->assertInstanceOf(Response::class, $mock->offersAction($this->requestMock, $this->responseMock, ['hash' => 'asdasd']));
  }

  public function testOfferListActionWithNoApplication()
  {
    $mock = $this->mock;

    $this->repositoryMock->expects($this->once())
      ->method('getByHash')
      ->willReturn(null);

    $mock->expects($this->once())
      ->method('getAppRepository')
      ->willReturn($this->repositoryMock);

    $this->expectException(NotFoundException::class);

    $mock->offersAction($this->requestMock, $this->responseMock, ['hash' => 'asdasd']);
  }
}
