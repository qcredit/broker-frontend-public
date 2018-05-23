<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 11.04.18
 * Time: 10:19
 */

namespace Tests\Unit\Controller;

use App\Base\Factory\UserFactory;
use App\Base\Persistence\Doctrine\UserRepository;
use App\Base\Validator\UserValidator;
use App\Controller\Admin\UserController;
use App\Model\User;
use Broker\System\BaseTest;
use Slim\Container;
use Slim\Flash\Messages;
use Slim\Http\Request;
use Slim\Http\Response;

class UserControllerTest extends BaseTest
{
  protected $mock;
  protected $repositoryMock;
  protected $requestMock;
  protected $responseMock;
  protected $factoryMock;
  protected $containerMock;

  public function setUp()
  {
    $this->mock = $this->getMockBuilder(UserController::class)
      ->disableOriginalConstructor()
      ->setMethods(['render', 'getUserRepository', 'getUserFactory', 'getValidator', 'getContainer', 'findEntity'])
      ->getMock();
    $this->repositoryMock = $this->getMockBuilder(UserRepository::class)
      ->disableOriginalConstructor()
      ->setMethods(['getAll', 'getOneBy', 'save', 'delete'])
      ->getMock();

    $this->factoryMock = $this->getMockBuilder(UserFactory::class)
      ->disableOriginalConstructor()
      ->setMethods(['create'])
      ->getMock();

    $this->mock->method('render')
      ->willReturnArgument(2);
    $this->mock->method('getValidator')
      ->willReturn($this->createMock(UserValidator::class));

    $this->requestMock = $this->createMock(Request::class);
    $this->responseMock = $this->createMock(Response::class);
    $this->containerMock = $this->createMock(Container::class);
  }

  public function testIndexAction()
  {
    $users = [
      (new User())->setEmail('slava@bogu.ee'),
      new User()
    ];

    $mock = $this->mock;

    $mock->expects($this->once())
      ->method('render')
      ->willReturnArgument(2);

    $repositoryMock = $this->repositoryMock;
    $repositoryMock->method('getAll')
      ->willReturn($users);

    $mock->expects($this->once())
      ->method('getUserRepository')
      ->willReturn($repositoryMock);

    $data = [
      'users' => $users
    ];

    $result = $mock->indexAction($this->requestMock, $this->responseMock, $data);
    $this->assertTrue(is_array($result));
    $this->assertInstanceOf(User::class, $result['users'][0]);
  }

  public function testNewAction()
  {
    $mock = $this->mock;

    $mock->expects($this->once())
      ->method('getUserFactory')
      ->willReturn($this->factoryMock);

    $data = [];

    $result = $mock->newAction($this->requestMock, $this->responseMock, $data);
    $this->assertArrayHasKey('user', $result);
    $this->assertInstanceOf(User::class, $result['user']);
  }

  public function testNewActionWithInvalidPost()
  {
    $mock = $this->mock;
    $userMock = $this->createMock(User::class);
    $userMock->method('validate')
      ->willReturn(true);

    $factoryMock = $this->factoryMock;
    $factoryMock->method('create')
      ->willReturn($userMock);

    $mock->expects($this->once())
      ->method('getUserFactory')
      ->willReturn($factoryMock);

    $this->requestMock->method('isPost')
      ->willReturn(true);
    $this->requestMock->method('getParsedBody')
      ->willReturn(['email' => 'aaa']);

    $result = $mock->newAction($this->requestMock, $this->responseMock, []);
    $this->assertArrayHasKey('user', $result);
    $this->assertInstanceOf(User::class, $result['user']);
  }

  public function testNewActionWithValidPost()
  {
    $mock = $this->mock;

    $data = [
      'email' => 'justin.bieber@gmail.com'
    ];

    $this->requestMock->method('isPost')
      ->willReturn(true);
    $this->requestMock->method('getParsedBody')
      ->willReturn($data);
    $this->responseMock->expects($this->once())
      ->method('withRedirect')
      ->willReturnSelf();

    $userMock = $this->getMockBuilder(User::class)
      ->setMethods(['validate', 'load'])
      ->getMock();
    $userMock->method('validate')
      ->willReturn(true);
    $userMock->method('load')
      ->willReturn(true);

    $factoryMock = $this->factoryMock;
    $factoryMock->method('create')
      ->willReturn($userMock);

    $mock->method('getUserFactory')
      ->willReturn($factoryMock);
    $mock->method('getUserRepository')
      ->willReturn($this->repositoryMock);

    $this->containerMock->method('get')
      ->willReturn($this->createMock(Messages::class));
    $mock->method('getContainer')
      ->willReturn($this->containerMock);

    $result = $mock->newAction($this->requestMock, $this->responseMock, []);
    $this->assertInstanceOf(Response::class, $result);
  }

  public function testDeleteAction()
  {
    $this->repositoryMock->method('delete')
      ->willReturn(true);
    $this->mock->method('getUserRepository')
      ->willReturn($this->repositoryMock);
    $this->mock->method('findEntity')
      ->willReturn(new User());

    $this->responseMock->expects($this->once())
      ->method('withRedirect')
      ->willReturnSelf();

    $data = [
      'id' => 1
    ];

    $result = $this->mock->deleteAction($this->requestMock, $this->responseMock, $data);
    $this->assertInstanceOf(Response::class, $result);
  }
}
