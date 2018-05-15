<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 26.04.18
 * Time: 15:20
 */

namespace Tests\Unit\Base\Repository;

use App\Base\Repository\MessageTemplateRepository;
use Broker\Domain\Entity\Application;
use Broker\Domain\Entity\Message;
use Broker\Domain\Entity\Offer;
use Broker\Domain\Factory\MessageFactory;
use Broker\Domain\Interfaces\Factory\MessageFactoryInterface;
use Broker\System\BaseTest;
use Slim\App;
use Slim\Container;
use Slim\Views\Twig;

class MessageTemplateRepositoryTest extends BaseTest
{
  protected $mock;
  protected $viewMock;
  protected $messageFactoryMock;

  public function setUp()
  {
    $this->mock = $this->getMockBuilder(MessageTemplateRepository::class)
      ->disableOriginalConstructor()
      ->setMethods(['getView', 'getMessageFactory', 'generateEmailContent'])
      ->getMock();
    $this->viewMock = $this->createMock(Twig::class);
    $this->messageFactoryMock = $this->getMockBuilder(MessageFactory::class)
      ->disableOriginalConstructor()
      ->setMethods(['create'])
      ->getMock();
  }

  public function test__construct()
  {
    $containerMock = $this->getMockBuilder(Container::class)
      ->disableOriginalConstructor()
      ->getMock();
    $instance = new MessageTemplateRepository($containerMock, $this->messageFactoryMock);

    $this->assertInstanceOf(Container::class, $instance->getContainer());
    $this->assertInstanceOf(MessageFactoryInterface::class, $instance->getMessageFactory());
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

  public function testGetOfferLinkMessage()
  {
    $app = new Application();
    $app->setEmail('peeter@eeter.ee');
    $this->messageFactoryMock->method('create')
      ->willReturn(new Message());

    $this->mock->method('getMessageFactory')
      ->willReturn($this->messageFactoryMock);

    $this->mock->method('generateEmailContent')
      ->with($this->equalTo('mail/offer-link.twig'))
      ->willReturn('string');

    $result = $this->mock->getOfferLinkMessage($app);
    $this->assertInstanceOf(Message::class, $result);
    $this->assertSame('string', $result->getBody());
  }

  public function testGetOfferConfirmationMessage()
  {
    $offer = new Offer();
    $offer->setApplication((new Application())->setEmail('peeter@eeter.ee'));
    $this->messageFactoryMock->method('create')
      ->willReturn(new Message());

    $this->mock->method('getMessageFactory')
      ->willReturn($this->messageFactoryMock);

    $this->mock->method('generateEmailContent')
      ->with($this->equalTo('mail/offer-confirmation.twig'))
      ->willReturn('string');

    $result = $this->mock->getOfferConfirmationMessage($offer);
    $this->assertInstanceOf(Message::class, $result);
    $this->assertSame('string', $result->getBody());
  }
}
