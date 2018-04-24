<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 12.04.18
 * Time: 09:52
 */

namespace Tests\Unit\Base\Components;

use App\Base\Components\AuthHandler;
use App\Base\Interfaces\AuthenticationServiceInterface;
use App\Base\Persistence\Doctrine\UserRepository;
use Broker\System\BaseTest;
use App\Model\User;
use Slim\Container;
use SlimSession\Helper;

class AuthHandlerTest extends BaseTest
{
  protected $mock;
  protected $authServiceMock;
  protected $repositoryMock;
  protected $containerMock;

  public function setUp()
  {
    $this->mock = $this->getMockBuilder(AuthHandler::class)
      ->disableOriginalConstructor()
      ->setMethods(['getAuthService', 'getUserRepository', 'findUser', 'getUser'])
      ->getMock();
    $this->mock->setPayload([]);

    $this->authServiceMock = $this->getMockBuilder(AuthenticationServiceInterface::class)
      ->setMethods(['authenticate', 'setPayload', 'getName'])
      ->getMock();
    $this->authServiceMock->method('setPayload')
      ->willReturnSelf();

    $this->repositoryMock = $this->getMockBuilder(UserRepository::class)
      ->disableOriginalConstructor()
      ->setMethods(['getByEmail', 'save'])
      ->getMock();

    $this->containerMock = $this->getMockBuilder(Container::class)
      ->disableOriginalConstructor()
      ->setMethods(['get'])
      ->getMock();
  }

  public function testLogin()
  {
    $mock = $this->getMockBuilder(AuthHandler::class)
      ->disableOriginalConstructor()
      ->setMethods(['getAuthService', 'getUserRepository', 'findUser', 'getUser', 'setupSession'])
      ->getMock();

    $this->authServiceMock->expects($this->once())
      ->method('authenticate')
      ->willReturn(true);

    $mock->method('getAuthService')
      ->willReturn($this->authServiceMock);
    $mock->method('getUser')
      ->willReturn(new User());
    $mock->setPayload([]);

    $this->assertTrue($mock->login());
  }

  public function testLoginUnsuccessful()
  {
    $this->authServiceMock->expects($this->once())
      ->method('authenticate')
      ->willReturn(false);

    $this->mock->method('getAuthService')
      ->willReturn($this->authServiceMock);
    $this->mock->method('getUser')
      ->willReturn(new User());

    $this->assertFalse($this->mock->login());
  }

  public function testLogout()
  {
    $sessionMock = $this->createMock(Helper::class);
    $sessionMock->expects($this->exactly(2))
      ->method('delete');

    $this->containerMock->method('get')
      ->willReturn($sessionMock);
    $this->mock->setContainer($this->containerMock);

    $this->mock->logout();
  }

  public function testFindUser()
  {
    $mock = $this->getMockBuilder(AuthHandler::class)
      ->disableOriginalConstructor()
      ->setMethods(['getAuthService', 'getUserRepository'])
      ->getMock();

    $this->repositoryMock->method('getByEmail')
      ->willReturn(new User());

    $mock->expects($this->once())
      ->method('getUserRepository')
      ->willReturn($this->repositoryMock);
    $mock->setPayload(['email' => 'kaera@jaan.ee']);

    $this->invokeMethod($mock, 'findUser', []);

    $this->assertInstanceOf(User::class, $mock->getUser());
  }

  public function testSetupSession()
  {
    $mock = $this->getMockBuilder(AuthHandler::class)
      ->disableOriginalConstructor()
      ->setMethods(['getUser', 'getUserRepository', 'getContainer'])
      ->getMock();

    $userMock = $this->createMock(User::class);
    $userMock->expects($this->once())
      ->method('generateAuthKey');

    $mock->expects($this->once())
      ->method('getUser')
      ->willReturn($userMock);

    $sessionMock = $this->createMock(Helper::class);
    $sessionMock->expects($this->exactly(2))
      ->method('set');

    $this->containerMock->method('get')
      ->willReturn($sessionMock);

    $mock->expects($this->once())
      ->method('getContainer')
      ->willReturn($this->containerMock);

    $mock->expects($this->once())
      ->method('getUserRepository')
      ->willReturn($this->repositoryMock);

    $this->invokeMethod($mock, 'setupSession', []);
  }
}
