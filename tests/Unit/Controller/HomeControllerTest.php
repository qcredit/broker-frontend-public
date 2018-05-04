<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 04.05.18
 * Time: 10:34
 */

namespace Tests\Unit\Controller;

use App\Controller\HomeController;
use App\Middleware\LanguageSwitcher;
use PHPUnit\Framework\TestCase;
use Slim\Container;
use Slim\Http\Cookies;
use Slim\Http\Request;
use Slim\Http\Response;
use SlimSession\Helper;

class HomeControllerTest extends TestCase
{
  protected $mock;
  protected $containerMock;
  protected $requestMock;
  protected $responseMock;

  public function setUp()
  {
    $this->mock = $this->getMockBuilder(HomeController::class)
      ->disableOriginalConstructor()
      ->setMethods(['getSession'])
      ->getMock();
    $this->containerMock = $this->createMock(Container::class, ['get']);
    $this->requestMock = $this->createMock(Request::class, ['getQueryParam']);
    $this->responseMock = $this->createMock(Response::class);
  }

  public function test__construct()
  {
    $instance = new HomeController($this->containerMock);
    $this->assertInstanceOf(Container::class, $instance->getContainer());
  }

  public function testLanguageAction()
  {
    $cookiesMock = $this->createMock(Helper::class, ['set']);
    $cookiesMock->expects($this->once())
      ->method('set')
      ->with(LanguageSwitcher::COOKIE_LANGUAGE, $this->equalTo('et_ET'));

    $this->mock->method('getSession')
      ->willReturn($cookiesMock);
    $this->responseMock->method('withRedirect')
      ->willReturn('redirect');
    $this->requestMock->method('getQueryParam')
      ->willReturn('et');

    $this->assertSame('redirect', $this->mock->languageAction($this->requestMock, $this->responseMock, []));
  }
}
