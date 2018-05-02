<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 09.04.18
 * Time: 11:40
 */

namespace Tests\Unit\Controller;

use App\Base\Persistence\Doctrine\OfferRepository;
use App\Base\Persistence\Doctrine\PartnerRepository;
use App\Controller\ApplicationController;
use Broker\Domain\Service\ChooseOfferService;
use Broker\System\BaseTest;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Container;
use Slim\Views\Twig;
use App\Base\Persistence\Doctrine\ApplicationRepository;
use Broker\Domain\Entity\Application;
use Broker\Domain\Entity\Offer;
use Broker\Domain\Entity\Partner;

class ApplicationControllerTest extends BaseTest
{
  protected $mock;
  protected $requestMock;
  protected $responseMock;
  protected $containerMock;
  protected $repositoryMock;
  protected $offerRepoMock;

  public function setUp()
  {
    $this->mock = $this->getMockBuilder(ApplicationController::class)
      ->disableOriginalConstructor()
      ->setMethods([
        'getAppRepository',
        'getContainer',
        'render',
        'findEntity',
        'getOfferRepository',
        'getChooseOfferService',
        'generateOfferConfirmationMessage'
      ])
      ->getMock();
    $this->requestMock = $this->createMock(Request::class);
    $this->responseMock = $this->createMock(Response::class);
    $this->containerMock = $this->getMockBuilder(Container::class)
      ->setMethods(['get'])
      ->getMock();
    $this->repositoryMock = $this->getMockBuilder(ApplicationRepository::class)
      ->disableOriginalConstructor()
      ->setMethods(['getAll', 'getOneBy', 'getByHash'])
      ->getMock();
    $this->offerRepoMock = $this->getMockBuilder(OfferRepository::class)
      ->disableOriginalConstructor()
      ->setMethods(['getAll', 'getOneBy', 'getByHash'])
      ->getMock();

    $twigMock = $this->getMockBuilder(Twig::class)
      ->disableOriginalConstructor()
      ->getMock();
    $twigMock->method('render')
      ->willReturn(new Response());

    $this->containerMock->method('get')
      ->willReturn($twigMock);

    $this->mock->method('getContainer')->willReturn($this->containerMock);
    //$this->mock->method('render')->willReturnArgument(2);
  }

  public function testOfferListAction()
  {
    $mock = $this->mock;

    $mock->expects($this->once())
      ->method('findEntity')
      ->willReturn(new Application());

    $this->mock->method('render')->willReturnArgument(2);

    $result = $mock->offersAction($this->requestMock, $this->responseMock, ['hash' => 'asdasd']);

    $this->assertTrue(is_array($result));
    $this->assertInstanceOf(Application::class, $result['application']);
  }

  public function testSelectOfferAction()
  {
    $this->offerRepoMock->method('getOneBy')
      ->willReturn(new Offer());
    $this->mock->method('getOfferRepository')
      ->willReturn($this->offerRepoMock);

    $this->mock->method('findEntity')
      ->willReturn(new Application());

    $this->mock->method('render')->willReturnArgument(2);
    $result = $this->mock->selectOfferAction($this->requestMock, $this->responseMock, ['hash' => 'asd']);
    $this->assertTrue(is_array($result));
    $this->assertInstanceOf(Application::class, $result['application']);
    $this->assertInstanceOf(Offer::class, $result['offer']);
  }

  public function testSelectOfferActionWithPost()
  {
    $this->offerRepoMock->method('getOneBy')
      ->willReturn(new Offer());
    $this->mock->method('getOfferRepository')
      ->willReturn($this->offerRepoMock);
    $serviceMock = $this->getMockBuilder(ChooseOfferService::class)
      ->disableOriginalConstructor()
      ->setMethods(['run', 'setData'])
      ->getMock();
    $serviceMock->expects($this->once())
      ->method('run')
      ->willReturn(true);
    $serviceMock->expects($this->once())
      ->method('setData')
      ->willReturnSelf();

    $this->mock->expects($this->once())
      ->method('getChooseOfferService')
      ->willReturn($serviceMock);

    $this->requestMock->expects($this->once())
      ->method('isPost')
      ->willReturn(true);
    $this->requestMock->expects($this->once())
      ->method('getParsedBody')
      ->willReturn(['oh' => 'my']);
    $this->responseMock->method('withRedirect')
      ->willReturnSelf();

    $this->mock->method('render')->willReturnArgument(1);

    $result = $this->mock->selectOfferAction($this->requestMock, $this->responseMock, ['hash' => 'asd']);
    $this->assertSame('application/thankyou.twig', $result);
  }

  public function testSelectOfferActionWithInvalidPost()
  {
    $this->offerRepoMock->method('getOneBy')
      ->willReturn(new Offer());
    $this->mock->method('getOfferRepository')
      ->willReturn($this->offerRepoMock);
    $serviceMock = $this->getMockBuilder(ChooseOfferService::class)
      ->disableOriginalConstructor()
      ->setMethods(['run', 'setData'])
      ->getMock();
    $serviceMock->expects($this->once())
      ->method('run')
      ->willReturn(false);
    $serviceMock->expects($this->once())
      ->method('setData')
      ->willReturnSelf();

    $this->mock->expects($this->once())
      ->method('getChooseOfferService')
      ->willReturn($serviceMock);

    $this->requestMock->expects($this->once())
      ->method('isPost')
      ->willReturn(true);
    $this->requestMock->expects($this->once())
      ->method('getParsedBody')
      ->willReturn(['oh' => 'my']);
    $this->responseMock->method('withRedirect')
      ->willReturnSelf();

    $this->mock->method('render')->willReturnArgument(2);

    $result = $this->mock->selectOfferAction($this->requestMock, $this->responseMock, ['hash' => 'asd']);
    $this->assertTrue(is_array($result));
    $this->assertInstanceOf(Offer::class, $result['offer']);
  }

  public function testSelectOfferActionNoOfferFound()
  {
    $this->offerRepoMock->method('getOneBy')
      ->willReturn(null);
    $this->mock->method('getOfferRepository')
      ->willReturn($this->offerRepoMock);

    $this->mock->method('findEntity')
      ->willReturn(new Application());

    $this->expectException(NotFoundException::class);

    $result = $this->mock->selectOfferAction($this->requestMock, $this->responseMock, ['hash' => 'asd']);
  }

  public function testGetPartners()
  {
    $mock = $this->getMockBuilder(ApplicationController::class)
      ->disableOriginalConstructor()
      ->setMethods(['getContainer'])
      ->getMock();
    $partnerRepoMock = $this->getMockBuilder(PartnerRepository::class)
      ->disableOriginalConstructor()
      ->setMethods(['getActivePartners'])
      ->getMock();
    $partners = [new Partner(), new Partner()];
    $partnerRepoMock->expects($this->once())
      ->method('getActivePartners')
      ->willReturn($partners);
    $containerMock = $this->createMock(Container::class, ['get']);
    $containerMock->method('get')
      ->willReturn($partnerRepoMock);
    $mock->expects($this->once())
      ->method('getContainer')
      ->willReturn($containerMock);

    $result = $this->invokeMethod($mock, 'getPartners', []);
    $this->assertTrue(is_array($result));
    $this->assertSame($partners[0], $result[0]);
  }

  public function testGetPartnersSchemas()
  {

  }
}
