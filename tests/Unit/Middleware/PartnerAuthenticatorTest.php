<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 10.05.18
 * Time: 10:26
 */

namespace Tests\Unit\Middleware;

use App\Middleware\PartnerAuthenticator;
use Broker\System\BaseTest;
use Broker\Domain\Entity\Partner;
use Slim\App;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class PartnerAuthenticatorTest extends BaseTest
{
  protected $appMock;
  protected $requestMock;
  protected $responseMock;
  protected $mock;

  public function setUp()
  {
    $this->appMock = $this->createMock(App::class);
    $this->requestMock = $this->createMock(Request::class);
    $this->responseMock = $this->createMock(Response::class);
    $this->mock = $this->getMockBuilder(PartnerAuthenticator::class)
      ->disableOriginalConstructor()
      ->setMethods(['getPartners'])
      ->getMock();
  }

  public function test__construct()
  {
    $appMock = $this->getMockBuilder(App::class)
      ->disableOriginalConstructor()
      ->setMethods(['getContainer'])
      ->getMock();
    $containerMock = $this->getMockBuilder(Container::class)
      ->disableOriginalConstructor()
      ->getMock();
    $appMock->method('getContainer')
      ->willReturn($containerMock);

    $instance = new PartnerAuthenticator($appMock);
    $this->assertInstanceOf(Container::class, $instance->getContainer());
  }

  public function testDenyAccess()
  {
    $this->responseMock->method('withStatus')
      ->willReturnArgument(0);
    $result = $this->invokeMethod($this->mock, 'denyAccess', [$this->responseMock, '']);
    $this->assertSame(401, $result);
  }

  public function testGetAuthTypeAndKey()
  {
    $header = 'Basic sdxsaxxaasx';

    $result = $this->invokeMethod($this->mock, 'getAuthTypeAndKey', [$header]);
    $this->assertSame(explode(' ', $header), $result);
  }

  public function testIdentifyAuthMethod()
  {
    $header = ['Basic xyz'];

    $result = $this->invokeMethod($this->mock, 'identifyAuthMethod', [$header]);

    $this->assertSame('Basic', $result);
  }

  public function testIdentifyUnknownAuthMethod()
  {
    $header = ['Bearer xyz'];

    $result = $this->invokeMethod($this->mock, 'identifyAuthMethod', [$header]);

    $this->assertFalse($result);
  }

  public function testAuthorizePartnerMethod()
  {
    $partner = (new Partner())->setAttribute('authorization', 'Basic')->setAttribute('localUsername', 'antskan')->setAttribute('localPassword', 'toss');
    $header = ['Basic YW50c2thbjp0b3Nz'];

    $result = $this->invokeMethod($this->mock, 'authorizePartnerMethod', [$partner, $header]);
    $this->assertTrue($result);
  }

  public function testAuthorizeInvalidPartnerMethod()
  {
    $partner = (new Partner())->setAttribute('authorization', 'Bearer');
    $header = ['Basic YW50c2thbjp0b3Nz'];

    $result = $this->invokeMethod($this->mock, 'authorizePartnerMethod', [$partner, $header]);
    $this->assertFalse($result);
  }

  public function testAuthorizePartnerMethodWithInvalidData()
  {
    $partner = (new Partner())->setAttribute('authorization', 'Basic')->setAttribute('localUsername', 'antskan')->setAttribute('localPassword', 'tossike');
    $header = ['Basic YW50c2thbjp0b3Nz'];

    $result = $this->invokeMethod($this->mock, 'authorizePartnerMethod', [$partner, $header]);
    $this->assertFalse($result);
  }

  public function testAuthorizeRequestsWillAllFail()
  {
    $partnersArray = [
      (new Partner())->setAttribute('authorization', 'Bearer'),
      (new Partner())->setAttribute('authorization', 'Basic')->setAttribute('localUsername', 'antskan')->setAttribute('localPassword', 'toss')
    ];

    $mock = $this->getMockBuilder(PartnerAuthenticator::class)
      ->disableOriginalConstructor()
      ->setMethods(['authorizePartner', 'getPartners'])
      ->getMock();

    $mock->method('getPartners')
      ->willReturn($partnersArray);
    $mock->method('authorizePartner')
      ->willReturn(false);

    $this->assertFalse($this->invokeMethod($mock, 'authorizeRequest', [[]]));
  }

  public function testAuthorizeRequestWithSuccess()
  {
    $partnersArray = [
      (new Partner())->setAttribute('authorization', 'Bearer'),
      (new Partner())->setAttribute('authorization', 'Basic')->setAttribute('localUsername', 'antskan')->setAttribute('localPassword', 'toss')
    ];

    $mock = $this->getMockBuilder(PartnerAuthenticator::class)
      ->disableOriginalConstructor()
      ->setMethods(['authorizePartner', 'getPartners'])
      ->getMock();

    $mock->method('getPartners')
      ->willReturn($partnersArray);
    $mock->method('authorizePartner')
      ->willReturn(true);

    $this->assertTrue($this->invokeMethod($mock, 'authorizeRequest', [[]]));
  }
}
