<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 05.06.18
 * Time: 10:24
 */

namespace Tests\Unit\Base\Event;

use App\Base\Event\BeforeNewApplicationServiceListener;
use App\Model\ApplicationForm;
use Broker\Domain\Service\NewApplicationService;
use Broker\System\BaseTest;
use PHPUnit\Framework\TestCase;

class BeforeNewApplicationServiceListenerTest extends BaseTest
{
  protected $instance;
  protected $mock;
  protected $emitter;
  protected $methodMock;

  public function setUp()
  {
    $this->mock = $this->getMockBuilder(BeforeNewApplicationServiceListener::class)
      ->getMock();
    $this->methodMock = $this->getMockBuilder(BeforeNewApplicationServiceListener::class)
      ->setMethods(['modifyPhone'])
      ->getMock();
    $this->emitter = $this->createMock(NewApplicationService::class);
  }

  public function test__invoke()
  {
    $this->emitter->method('getPostData')
      ->willReturn([]);

    $this->assertNull($this->methodMock->__invoke($this->emitter, 'test'));
  }

  public function test__invokeWithPhone()
  {
    $this->emitter->method('getPostData')
      ->willReturn([ApplicationForm::ATTR_PHONE => '123123']);

    $this->methodMock->expects($this->once())
      ->method('modifyPhone');

    $this->assertNull($this->methodMock->__invoke($this->emitter, 'test'));
  }

  public function testModifyPhoneWithoutCountryCode()
  {
    $this->emitter->method('getPostData')
      ->willReturn([ApplicationForm::ATTR_PHONE => '123456789']);

    $result = $this->invokeMethod($this->mock, 'modifyPhone', [$this->emitter]);

    $this->assertSame('+48123456789', $result);
  }

  public function testModifyPhoneWithCountryCode()
  {
    $this->emitter->method('getPostData')
      ->willReturn([ApplicationForm::ATTR_PHONE => '+48123456789']);

    $result = $this->invokeMethod($this->mock, 'modifyPhone', [$this->emitter]);

    $this->assertSame('+48123456789', $result);
  }
}
