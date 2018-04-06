<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 06.04.18
 * Time: 17:04
 */

namespace Tests\Unit\Controller;

use App\Controllers\Admin\AdminApplicationController;
use Broker\Domain\Entity\Application;
use Broker\Persistence\Doctrine\ApplicationRepository;
use PHPUnit\Framework\TestCase;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class AdminApplicationControllerTest extends TestCase
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
      ->setMethods(['getAppRepository', 'getContainer'])
      ->getMock();

    $this->repositoryMock = $this->getMockBuilder(ApplicationRepository::class)
      ->disableOriginalConstructor()
      ->setMethods(['getAll', 'getOneBy'])
      ->getMock();

    $twigMock = $this->getMockBuilder(Twig::class)
      ->disableOriginalConstructor()
      ->getMock();
    $twigMock->method('render')
      ->willReturn(new Response());

    $this->requestMock = $this->createMock(Request::class);
    $this->responseMock = $this->createMock(Response::class);
    $this->containerMock = $this->getMockBuilder(Container::class)
      ->setMethods(['get'])
      ->getMock();
    $this->containerMock->method('get')
      ->willReturn($twigMock);
    $this->mock->method('getContainer')->willReturn($this->containerMock);

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

    $this->assertInstanceOf(Response::class, $mock->indexAction($this->requestMock, $this->responseMock, []));
  }

  public function testViewAction()
  {
    $this->repositoryMock->method('getOneBy')
      ->willReturn(new Application());

    $this->mock->expects($this->once())
      ->method('getAppRepository')
      ->willReturn($this->repositoryMock);

    $this->assertInstanceOf(Response::class, $this->mock->viewAction($this->requestMock, $this->responseMock, []));
  }
}
