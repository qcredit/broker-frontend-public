<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 17.05.18
 * Time: 08:48
 */

namespace Tests\Unit\Controller;

use Aasa\CommonWebSDK\BlogServiceAWS;
use App\Controller\BlogController;
use Broker\System\BaseTest;
use Slim\Container;
use Tests\Helpers\ControllerTestTrait;

class BlogControllerTest extends BaseTest
{
  use ControllerTestTrait;
  private $serviceMock;
  private $mock;

  public function setUp()
  {
    $this->setupMocks();
    $this->serviceMock = $this->getMockBuilder(BlogServiceAWS::class)
      ->disableOriginalConstructor()
      ->getMock();
    $this->mock = $this->getMockBuilder(BlogController::class)
      ->disableOriginalConstructor()
      ->setMethods(['getBlogService', 'render'])
      ->getMock();
  }

  public function test__construct()
  {
    $instance = new BlogController($this->serviceMock, $this->containerMock);
    $this->assertInstanceOf(BlogServiceAWS::class, $instance->getBlogService());
    $this->assertInstanceOf(Container::class, $instance->getContainer());
  }

  public function testIndexAction()
  {
    $posts = ['firstpost'];
    $this->serviceMock->method('select')
      ->willReturn($posts);
    $this->mock->method('getBlogService')
      ->willReturn($this->serviceMock);

    $this->mock->method('render')
      ->with($this->equalTo($this->responseMock), 'blog/index.twig', $this->equalTo(['posts' => $posts]))
      ->willReturnArgument(2);

    $result = $this->mock->indexAction($this->requestMock, $this->responseMock, []);
    $this->assertSame($posts, $result['posts']);
  }

  public function testViewAction()
  {
    $post = ['asdasd'];
    $slug = 'some-post';
    $this->serviceMock->method('selectByUrl')
      ->with($this->equalTo($slug))
      ->willReturn($post);
    $this->mock->method('getBlogService')
      ->willReturn($this->serviceMock);

    $this->mock->method('render')
      ->with($this->equalTo($this->responseMock), 'blog/view.twig', $this->equalTo(['post' => $post]))
      ->willReturnArgument(2);

    $result = $this->mock->viewAction($this->requestMock, $this->responseMock, ['slug' => $slug]);
    $this->assertSame($post, $result['post']);
  }
}
