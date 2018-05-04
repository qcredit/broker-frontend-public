<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 03.05.18
 * Time: 17:10
 */

namespace Tests\Unit\Middleware;

use App\Middleware\LanguageSwitcher;
use Broker\System\BaseTest;
use Monolog\Logger;
use Slim\App;
use Slim\Http\Cookies;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Container;

class LanguageSwitcherTest extends BaseTest
{
  protected $appMock;
  protected $mock;
  protected $altMock;
  protected $requestMock;
  protected $responseMock;
  protected $containerMock;

  public function setUp()/* The :void return type declaration that should be here would cause a BC issue */
  {
    parent::setUp();

    $this->appMock = $this->getMockBuilder(App::class)
      ->disableOriginalConstructor()
      ->setMethods()
      ->getMock();

    $this->mock = $this->getMockBuilder(LanguageSwitcher::class)
      ->disableOriginalConstructor()
      ->setMethods(['getBrowserLanguages', 'isLanguageSetByCookie', 'setLanguageByCookie', 'setLanguageByBrowser'])
      ->getMock();

    $this->altMock = $this->getMockBuilder(LanguageSwitcher::class)
      ->disableOriginalConstructor()
      ->setMethods(['getPreferredLanguage', 'setLocale', 'putenv', 'bindTextDomain', 'setLanguage'])
      ->getMock();

    $this->containerMock = $this->getMockBuilder(Container::class)
      ->setMethods(['get'])
      ->getMock();

    $this->requestMock = $this->createMock(Request::class);
    $this->responseMock = $this->createMock(Response::class);
  }

  public function test__construct()
  {
    $instance = new LanguageSwitcher($this->appMock);

    $this->assertInstanceOf(App::class, $instance->getApp());
  }

  public function testGetPreferredLanguage()
  {
    $this->mock->method('getBrowserLanguages')
      ->willReturn('et-EE,et;q=0.9,en-US;q=0.8,en;q=0.7');

    $this->assertSame('et_EE', $this->invokeMethod($this->mock, 'getPreferredLanguage', []));
  }

  public function testGetPreferredLanguagesWithNoBrowserPreference()
  {
    $this->mock->method('getBrowserLanguages')
      ->willReturn('');

    $this->assertSame(null, $this->invokeMethod($this->mock, 'getPreferredLanguage', []));
  }

  public function testGetPreferredLanguagesWithInvalidBrowserPreference()
  {
    $this->mock->method('getBrowserLanguages')
      ->willReturn('et_EE,et;q=0.9');

    $this->assertSame(null, $this->invokeMethod($this->mock, 'getPreferredLanguage', []));
  }

  public function test__invokeAndSetByCookie()
  {
    $this->mock->method('isLanguageSetByCookie')
      ->willReturn(true);

    $this->mock->expects($this->once())
      ->method('setLanguageByCookie');

    $fn = function ($request, $response) { return 'tere'; };

    $this->mock->__invoke($this->requestMock, $this->responseMock, $fn);
  }

  public function test__invokeAndSetByBrowser()
  {
    $this->mock->method('isLanguageSetByCookie')
      ->willReturn(false);

    $this->mock->expects($this->never())
      ->method('setLanguageByCookie');
    $this->mock->expects($this->once())
      ->method('setLanguageByBrowser');

    $fn = function ($request, $response) { return 'tere'; };

    $this->mock->__invoke($this->requestMock, $this->responseMock, $fn);
  }

  public function testSetLanguageWithInvalidLocale()
  {
    $mock = $this->getMockBuilder(LanguageSwitcher::class)
      ->disableOriginalConstructor()
      ->setMethods(['setLocale', 'bindTextDomain'])
      ->getMock();
    $mock->method('setLocale')
      ->willReturn(false);
    $this->containerMock->method('get')
      ->willReturn($this->createMock(Logger::class));
    $mock->setContainer($this->containerMock);

    $mock->expects($this->never())
      ->method('bindTextDomain');

    $this->assertFalse($this->invokeMethod($mock, 'setLanguage', ['et_EE']));
  }

  public function testSetLanguage()
  {
    $mock = $this->getMockBuilder(LanguageSwitcher::class)
      ->disableOriginalConstructor()
      ->setMethods(['setLocale', 'bindTextDomain'])
      ->getMock();
    $mock->method('setLocale')
      ->willReturn(true);
    $this->containerMock->method('get')
      ->willReturn($this->createMock(Logger::class));
    $mock->setContainer($this->containerMock);

    $mock->expects($this->once())
      ->method('bindTextDomain');

    $this->assertTrue($this->invokeMethod($mock, 'setLanguage', ['et_EE']));
  }

  public function testIsLanguageSetByCookie()
  {
    $cookiesMock = $this->createMock(Cookies::class, ['get']);
    $cookiesMock->method('get')
      ->willReturn('yees');
    $this->containerMock->method('get')
      ->willReturn($cookiesMock);
    $this->altMock->setContainer($this->containerMock);

    $this->assertSame('yees', $this->invokeMethod($this->altMock, 'isLanguageSetByCookie', []));
  }

  public function testIsLanguageSetByCookieReturnsFalse()
  {
    $cookiesMock = $this->createMock(Cookies::class, ['get']);
    $cookiesMock->method('get')
      ->willReturn(false);
    $this->containerMock->method('get')
      ->willReturn($cookiesMock);
    $this->altMock->setContainer($this->containerMock);

    $this->assertFalse($this->invokeMethod($this->altMock, 'isLanguageSetByCookie', []));
  }

  public function testSetLanguageByCookie()
  {
    $cookiesMock = $this->createMock(Cookies::class, ['get']);
    $cookiesMock->method('get')
      ->willReturn('et_EE');
    $this->containerMock->method('get')
      ->willReturn($cookiesMock);
    $this->altMock->setContainer($this->containerMock);
    $this->altMock->expects($this->once())
      ->method('setLanguage')
      ->with($this->equalTo('et_EE'));

    $this->invokeMethod($this->altMock, 'setLanguageByCookie', []);
  }

  public function testSetLanguageByBrowser()
  {
    $this->altMock->method('getPreferredLanguage')
      ->willReturn('et_EE');
    $this->altMock->expects($this->once())
      ->method('setLanguage')
      ->with($this->equalTo('et_EE'));

    $this->invokeMethod($this->altMock, 'setLanguageByBrowser', []);
  }

  public function testSetLanguageByBrowserFailsDueToNoInvalidPreferences()
  {
    $this->altMock->method('getPreferredLanguage')
      ->willReturn(null);

    $this->assertFalse($this->invokeMethod($this->altMock, 'setLanguageByBrowser', []));
  }
}