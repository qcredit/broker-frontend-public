<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 19.04.18
 * Time: 11:52
 */

namespace Tests\Unit\Controller\Admin;

use App\Base\Components\AuthHandler;
use App\Controller\Admin\LoginController;
use Broker\System\BaseTest;
use Slim\Http\Request;
use Slim\Http\Response;

class LoginControllerTest extends BaseTest
{
  protected $requestMock;
  protected $responseMock;
  protected $authHandlerMock;
  protected $mock;

  public function setUp()
  {
    $this->mock = $this->getMockBuilder(LoginController::class)
      ->disableOriginalConstructor()
      ->setMethods(['getAuthHandler', 'render'])
      ->getMock();
    $this->mock->method('render')
      ->willReturnArgument(2);
    $this->requestMock = $this->createMock(Request::class);
    $this->responseMock = $this->createMock(Response::class);
    $this->authHandlerMock = $this->createMock(AuthHandler::class);
  }

  public function testLoginAction()
  {
    $this->mock->expects($this->never())
      ->method('getAuthHandler');
    $result = $this->mock->loginAction($this->requestMock, $this->responseMock, []);
    $this->assertTrue(is_array($result));
    $this->assertEmpty($result);
  }

  public function testLoginActionWithPost()
  {
    $this->requestMock->method('isPost')
      ->willReturn(true);
    $this->requestMock->method('getParsedBody')
      ->willReturn([]);
    $this->responseMock->expects($this->once())
      ->method('withJson')
      ->willReturnArgument(0);

    $this->mock->expects($this->once())
      ->method('getAuthHandler')
      ->willReturn($this->authHandlerMock);

    $result = $this->mock->loginAction($this->requestMock, $this->responseMock, []);
    $this->assertTrue(is_array($result));
    $this->assertArrayHasKey('error', $result);
  }

  public function testLoginActionSuccess()
  {
    $this->requestMock->method('isPost')
      ->willReturn(true);
    $this->requestMock->method('getParsedBody')
      ->willReturn([]);
    $this->responseMock->expects($this->once())
      ->method('withJson')
      ->willReturnArgument(0);

    $this->authHandlerMock->method('login')
      ->willReturn(true);

    $this->mock->expects($this->once())
      ->method('getAuthHandler')
      ->willReturn($this->authHandlerMock);

    $result = $this->mock->loginAction($this->requestMock, $this->responseMock, []);
    $this->assertTrue(is_array($result));
    $this->assertArrayHasKey('success', $result);
  }

  public function testLogoutAction()
  {
    $this->authHandlerMock->expects($this->once())
      ->method('logout')
      ->willReturn(true);
    $this->mock->expects($this->once())
      ->method('getAuthHandler')
      ->willReturn($this->authHandlerMock);

    $this->responseMock->expects($this->once())
      ->method('withRedirect')
      ->willReturnArgument(0);

    $result = $this->mock->logoutAction($this->requestMock, $this->responseMock, []);
    $this->assertSame('/', $result);
  }
}
