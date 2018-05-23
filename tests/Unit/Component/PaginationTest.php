<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 22.05.18
 * Time: 11:28
 */

namespace Tests\Unit\Component;

use App\Component\Pagination;
use Broker\System\BaseTest;
use Slim\Http\Request;
use Slim\Http\Uri;

class PaginationTest extends BaseTest
{
  protected $instance;
  protected $requestMock;

  public function setUp()
  {
    $this->requestMock = $this->createMock(Request::class);
    $this->requestMock->method('getUri')
      ->willReturn($this->createMock(Uri::class));
  }

  protected function nextPageUrlReturns($returnValue, $pagination)
  {
    $this->assertSame($returnValue, $pagination->getNextPageUrl());
  }

  public function test__construct()
  {
    $instance = new Pagination($this->requestMock, 100, 10);

    $this->assertSame(100, $instance->getTotalCount());
    $this->assertSame(10, $instance->getLimit());
  }

  public function testSetCurrentPage()
  {
    $mock = $this->getMockBuilder(Pagination::class)
      ->disableOriginalConstructor()
      ->setMethods(['getQueryParam'])
      ->getMock();

    $mock->method('getQueryParam')
      ->willReturn(100);

    $this->invokeMethod($mock, 'setCurrentPage', []);
    $this->assertSame(100, $mock->getCurrentPage());
  }

  public function testSetTotalPages()
  {
    $mock = $this->getMockBuilder(Pagination::class)
      ->disableOriginalConstructor()
      ->setMethods(['getTotalCount', 'getLimit'])
      ->getMock();
    $mock->method('getTotalCount')
      ->willReturn(100);
    $mock->method('getLimit')
      ->willReturn(5);

    $this->invokeMethod($mock, 'setTotalPages', []);
    $this->assertSame(ceil(100/5), $mock->getTotalPages());
  }

  public function testSetNextPage()
  {
    $mock = $this->getMockBuilder(Pagination::class)
      ->disableOriginalConstructor()
      ->setMethods(['getTotalPages', 'getCurrentPage'])
      ->getMock();

    $mock->method('getTotalPages')
      ->willReturn(100);
    $mock->method('getCurrentPage')
      ->willReturn(20);
    $this->invokeMethod($mock, 'setNextPage', []);

    $this->assertSame(21, $mock->getNextPage());
  }

  public function testSetNextPageWhenThereIsNone()
  {
    $mock = $this->getMockBuilder(Pagination::class)
      ->disableOriginalConstructor()
      ->setMethods(['getTotalPages', 'getCurrentPage'])
      ->getMock();

    $mock->method('getTotalPages')
      ->willReturn(100);
    $mock->method('getCurrentPage')
      ->willReturn(100);
    $this->invokeMethod($mock, 'setNextPage', []);

    $this->assertNull($mock->getNextPage());
  }

  public function testGetPreviousPage()
  {
    $mock = $this->getMockBuilder(Pagination::class)
      ->disableOriginalConstructor()
      ->setMethods(['getTotalPages', 'getCurrentPage'])
      ->getMock();

    $mock->method('getCurrentPage')
      ->willReturn(23);
    $this->invokeMethod($mock, 'setPreviousPage', []);

    $this->assertSame(22, $mock->getPreviousPage());
  }

  public function testGetPreviousPageWhenThereIsNone()
  {
    $mock = $this->getMockBuilder(Pagination::class)
      ->disableOriginalConstructor()
      ->setMethods(['getTotalPages', 'getCurrentPage'])
      ->getMock();

    $mock->method('getCurrentPage')
      ->willReturn(1);
    $this->invokeMethod($mock, 'setPreviousPage', []);

    $this->assertNull($mock->getPreviousPage());
  }

  public function testSetCurrentOffsetOnFirstPage()
  {
    $mock = $this->getMockBuilder(Pagination::class)
      ->disableOriginalConstructor()
      ->setMethods(['getPreviousPage', 'getLimit'])
      ->getMock();

    $mock->method('getPreviousPage')
      ->willReturn(null);
    $mock->method('getLimit')
      ->willReturn(10);

    $this->invokeMethod($mock, 'setCurrentOffset', []);
    $this->assertSame(0, $mock->getOffset());
  }

  public function testSetCurrentOffsetOnRandomPage()
  {
    $mock = $this->getMockBuilder(Pagination::class)
      ->disableOriginalConstructor()
      ->setMethods(['getPreviousPage', 'getLimit'])
      ->getMock();

    $limit = random_int(2, 100);
    $prevPage = random_int(2, 100);

    $mock->method('getPreviousPage')
      ->willReturn($prevPage);
    $mock->method('getLimit')
      ->willReturn($limit);

    $this->invokeMethod($mock, 'setCurrentOffset', []);
    $this->assertSame($limit*$prevPage, $mock->getOffset());
  }

  public function testGetNextPageLinkIsEmpty()
  {
    $mock = $this->getMockBuilder(Pagination::class)
      ->disableOriginalConstructor()
      ->setMethods(['getNextPageUrl'])
      ->getMock();

    $mock->method('getNextPageUrl')
      ->willReturn('');

    $this->assertSame('', $mock->getNextPageLink());
  }

  public function testGetNextPageLink()
  {
    $mock = $this->getMockBuilder(Pagination::class)
      ->disableOriginalConstructor()
      ->setMethods(['getNextPageUrl'])
      ->getMock();

    $link = '/url/to/page';
    $mock->method('getNextPageUrl')
      ->willReturn($link);

    $result = $mock->getNextPageLink();
    $this->assertTrue(strpos($result, $link) !== false);
  }

  public function testGetPreviousPageLink()
  {
    $mock = $this->getMockBuilder(Pagination::class)
      ->disableOriginalConstructor()
      ->setMethods(['getPreviousPageUrl'])
      ->getMock();

    $link = '/url/to/page';
    $mock->method('getPreviousPageUrl')
      ->willReturn($link);

    $result = $mock->getPreviousPageLink();
    $this->assertTrue(strpos($result, $link) !== false);
  }

  public function testGetPreviousPageLinkThatsEmpty()
  {
    $mock = $this->getMockBuilder(Pagination::class)
      ->disableOriginalConstructor()
      ->setMethods(['getPreviousPageUrl'])
      ->getMock();

    $mock->method('getPreviousPageUrl')
      ->willReturn('');

    $this->assertSame('', $mock->getPreviousPageLink());
  }
}
