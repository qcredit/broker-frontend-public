<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 26.04.18
 * Time: 15:20
 */

namespace Tests\Unit\Base\Repository;

use App\Base\Repository\MessageTemplateRepository;
use Broker\Domain\Entity\Message;
use Broker\System\BaseTest;
use Slim\Views\Twig;

class MessageTemplateRepositoryTest extends BaseTest
{
  protected $mock;
  protected $viewMock;

  public function setUp()
  {
    $this->mock = $this->getMockBuilder(MessageTemplateRepository::class)
      ->disableOriginalConstructor()
      ->setMethods(['getView'])
      ->getMock();
    $this->viewMock = $this->createMock(Twig::class);
  }

  public function testGetTemplateByPath()
  {
    $this->mock->expects($this->once())
      ->method('getView')
      ->willReturn($this->viewMock);
    $this->viewMock->method('fetch')
      ->willReturnArgument(0);

    $template = 'some/viewfile.twig';
    $arguments = [
      'some' => 'variable'
    ];

    $result = $this->mock->getTemplateByPath($template, $arguments);
    $this->assertSame($template, $result);
  }
}
