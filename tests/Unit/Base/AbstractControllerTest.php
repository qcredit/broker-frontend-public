<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 10.04.18
 * Time: 10:42
 */

namespace Tests\Unit\Base;

use App\Component\AbstractController;
use Broker\System\BaseTest;
use Slim\Container;
use Slim\Flash\Messages;
use Slim\Http\Response;
use Slim\Views\Twig;

class AbstractControllerTest extends BaseTest
{
  protected $mock;
  protected $containerMock;
  protected $twigMock;

  public function setUp()
  {
    $this->mock = $this->getMockBuilder(AbstractController::class)
      ->disableOriginalConstructor()
      ->setMethods(['getContainer', 'getFlash', 'prepareFlashes'])
      ->getMockForAbstractClass();

    $this->containerMock = $this->getMockBuilder(Container::class)
      ->disableOriginalConstructor()
      ->setMethods(['get'])
      ->getMock();

    $this->twigMock = $this->getMockBuilder(Twig::class)
      ->disableOriginalConstructor()
      ->getMock();
    $this->twigMock->method('render')
      ->willReturn(new Response());

    $this->containerMock->method('get')
      ->with('view')
      ->willReturn($this->twigMock);

    $this->mock->method('getContainer')
      ->willReturn($this->containerMock);
  }

  public function testRender()
  {
    $response = new Response();

    $this->mock->expects($this->once())
      ->method('prepareFlashes');

    $result = $this->mock->render($response, 'adasd');

    $this->assertInstanceOf(Response::class, $result);
  }

  public function testPrepareFlashesSuccess()
  {
    $mock = $this->getMockBuilder(AbstractController::class)
      ->disableOriginalConstructor()
      ->setMethods(['getFlash'])
      ->getMockForAbstractClass();

    $flashMock = $this->getMockBuilder(Messages::class)
      ->disableOriginalConstructor()
      ->setMethods(['hasMessage', 'getFirstMessage'])
      ->getMock();

    $flashMock->expects($this->once())
      ->method('hasMessage')
      ->willReturn(true);

    $flashMock->expects($this->once())
      ->method('getFirstMessage')
      ->willReturn('eat sleep code repeat');

    $mock->method('getFlash')
      ->willReturn($flashMock);

    $data = [];

    $mock->prepareFlashes($data);

    $this->assertArrayHasKey('flash', $data);
    $this->assertArrayHasKey('success', $data['flash']);
  }

  public function testPrepareFlashesError()
  {
    $mock = $this->getMockBuilder(AbstractController::class)
      ->disableOriginalConstructor()
      ->setMethods(['getFlash'])
      ->getMockForAbstractClass();

    $flashMock = $this->getMockBuilder(Messages::class)
      ->disableOriginalConstructor()
      ->setMethods(['hasMessage', 'getFirstMessage'])
      ->getMock();

    $flashMock->method('hasMessage')
      ->willReturnOnConsecutiveCalls(false, true);

    $flashMock->expects($this->once())
      ->method('getFirstMessage')
      ->willReturn('eat sleep code repeat');

    $mock->method('getFlash')
      ->willReturn($flashMock);

    $data = [];

    $mock->prepareFlashes($data);

    $this->assertArrayHasKey('flash', $data);
    $this->assertArrayHasKey('error', $data['flash']);
  }
}
