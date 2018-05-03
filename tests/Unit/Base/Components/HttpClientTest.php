<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 29.04.18
 * Time: 17:50
 */

namespace Tests\Unit\Base\Components;

use App\Base\Components\HttpClient;
use Broker\System\BaseTest;

class HttpClientTest extends BaseTest
{

  public function test__construct()
  {
    $mock = $this->getMockBuilder(HttpClient::class)
      ->getMock();

    $this->assertInternalType('resource', $this->invokeMethod($mock, 'getClient', []));
  }

  public function testSetClientOptions()
  {
    $options = [
      CURLOPT_HTTPHEADER => 'hello',
      CURLOPT_RETURNTRANSFER => false
    ];

    $mock = $this->getMockBuilder(HttpClient::class)
      ->setMethods(['setClientOption'])
      ->getMock();

    $mock->expects($this->exactly(count($options)))
      ->method('setClientOption')
      ->willReturn(true);

    $mock->setClientOptions($options);
  }
}
