<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 17.05.18
 * Time: 08:52
 */

namespace Tests\Helpers;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Container;

trait ControllerTestTrait
{
  protected $requestMock;
  protected $responseMock;
  protected $containerMock;

  protected function setupMocks()
  {
    $this->requestMock = $this->createMock(Request::class);
    $this->responseMock = $this->createMock(Response::class);
    $this->containerMock = $this->getMockBuilder(Container::class)
      ->disableOriginalConstructor()
      ->getMock();

  }
}