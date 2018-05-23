<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 12.04.18
 * Time: 11:57
 */

namespace Tests\Unit\Base\Components;

use App\Component\GoogleAuthenticator;
use PHPUnit\Framework\TestCase;

class GoogleAuthenticatorTest extends TestCase
{

  public function testAuthenticate()
  {
    $mock = $this->getMockBuilder(GoogleAuthenticator::class)
      ->setMethods(['getPayload', 'getClient'])
      ->getMock();

    $clientMock = $this->getMockBuilder(\Google_Client::class)
      ->disableOriginalConstructor()
      ->setMethods(['verifyIdToken'])
      ->getMock();

    $clientMock->expects($this->once())
      ->method('verifyIdToken')
      ->willReturn(true);

    $mock->expects($this->once())
      ->method('getClient')
      ->willReturn($clientMock);

    $this->assertTrue($mock->authenticate());
  }
}
