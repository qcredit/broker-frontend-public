<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 14.06.18
 * Time: 08:26
 */

namespace Tests\Unit\Middleware;

use App\Middleware\ApplicationPost;
use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

class ApplicationPostTest extends TestCase
{
  protected $mock;
  protected $requestMock;
  protected $responseMock;

  public function setUp()
  {
    $this->mock = $this->getMockBuilder(ApplicationPost::class)
      ->disableOriginalConstructor()
      ->setMethods(['getSettings'])
      ->getMock();
    $this->requestMock = $this->createMock(Request::class);
    $this->responseMock = $this->createMock(Response::class);
  }

  public function test__construct()
  {
    $appMock = $this->createMock(App::class);
    $instance = new ApplicationPost($appMock);

    $this->assertInstanceOf(App::class, $instance->getApp());
  }

  public function test__invoke()
  {
    $this->mock->method('getSettings')
      ->willReturn([
        'baseUrl' => 'google.ee',
        'broker' => [
          'environment' => 'developer'
        ]
      ]);

    $this->responseMock->expects($this->never())
      ->method('withRedirect');

    $this->mock->__invoke($this->requestMock, $this->responseMock, function() {});
  }

  public function test__invokeOnProductionWithoutPost()
  {
    $this->mock->method('getSettings')
      ->willReturn([
        'baseUrl' => 'google.ee',
        'broker' => [
          'environment' => 'production'
        ]
      ]);

    $this->responseMock->expects($this->once())
      ->method('withRedirect');

    $this->mock->__invoke($this->requestMock, $this->responseMock, function() {});
  }

  public function test__invokeOnProductionWithPost()
  {
    $this->mock->method('getSettings')
      ->willReturn([
        'baseUrl' => 'google.ee',
        'broker' => [
          'environment' => 'production'
        ]
      ]);

    $this->responseMock->expects($this->never())
      ->method('withRedirect');
    $this->requestMock->method('getHeader')
      ->willReturn(['google.ee/myass']);
    $this->requestMock->method('isPost')
      ->willReturn(true);

    $this->mock->__invoke($this->requestMock, $this->responseMock, function() {});
  }
}
