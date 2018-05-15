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
use App\Base\Repository\MessageTemplateRepository;
use App\Base\Repository\PartnerDataMapperRepository;
use App\Controller\ApplicationController;
use Broker\Domain\Service\ChooseOfferService;
use Broker\Domain\Service\MessageDeliveryService;
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
use Broker\Domain\Entity\Message;

class ApplicationControllerTest extends BaseTest
{
  protected $mock;
  protected $requestMock;
  protected $responseMock;
  protected $containerMock;
  protected $repositoryMock;
  protected $offerRepoMock;
  protected $messageTemplateRepoMock;

  public function setUp()
  {
    $this->mock = $this->getMockBuilder(ApplicationController::class)
      ->disableOriginalConstructor()
      ->setMethods([
        'getAppRepository',
        'getContainer',
        'getPartners',
        'render',
        'findEntity',
        'getOfferRepository',
        'getChooseOfferService',
        'generateOfferConfirmationMessage',
        'serializeObjects',
        'getMessageTemplateRepository'
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
      ->setMethods(['getAll', 'getOneBy', 'getByHash', 'getOffersByApplication'])
      ->getMock();

    $twigMock = $this->getMockBuilder(Twig::class)
      ->disableOriginalConstructor()
      ->getMock();
    $twigMock->method('render')
      ->willReturn(new Response());

    $this->containerMock->method('get')
      ->willReturn($twigMock);

    $this->mock->method('getContainer')->willReturn($this->containerMock);
    $this->messageTemplateRepoMock = $this->getMockBuilder(MessageTemplateRepository::class)
      ->disableOriginalConstructor()
      ->setMethods(['getOfferConfirmationMessage'])
      ->getMock();
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
    $serviceMock->setMessageDeliveryService($this->createMock(MessageDeliveryService::class));
    $serviceMock->expects($this->once())
      ->method('run')
      ->willReturn(true);
    $serviceMock->expects($this->once())
      ->method('setData')
      ->willReturnSelf();

    $this->mock->expects($this->atLeastOnce())
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
    $this->messageTemplateRepoMock->method('getOfferConfirmationMessage')
      ->willReturn(new Message());
    $this->mock->method('getMessageTemplateRepository')
      ->willReturn($this->messageTemplateRepoMock);

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
    $serviceMock->setMessageDeliveryService($this->createMock(MessageDeliveryService::class));
    $serviceMock->expects($this->once())
      ->method('run')
      ->willReturn(false);
    $serviceMock->expects($this->once())
      ->method('setData')
      ->willReturnSelf();

    $this->mock->expects($this->atLeastOnce())
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

    $this->messageTemplateRepoMock->method('getOfferConfirmationMessage')
      ->willReturn(new Message());
    $this->mock->method('getMessageTemplateRepository')
      ->willReturn($this->messageTemplateRepoMock);

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

  public function testGetPartnersDataMappers()
  {
    $mock = $this->getMockBuilder(ApplicationController::class)
      ->disableOriginalConstructor()
      ->setMethods(['getPartnerDataMapperRepository', 'getPartners'])
      ->getMock();

    $dataMapperMock = $this->createMock(PartnerDataMapperRepository::class, ['getDataMapperByPartnerId']);
    $dataMapperMock->method('getDataMapperByPartnerId')
      ->with($this->equalTo('AASA'));
    $mock->expects($this->once())
      ->method('getPartnerDataMapperRepository')
      ->willReturn($dataMapperMock);
    $mock->expects($this->once())
      ->method('getPartners')
      ->willReturn([(new Partner())->setIdentifier('AASA')]);

    $result = $this->invokeMethod($mock, 'getPartnersDataMappers', []);
    $this->assertTrue(is_array($result));
  }

  public function testStatusAction()
  {
    $app = new Application();
    $app->setOffers([new Offer, new Offer]);
    $partnerArray = [new Partner(), new Partner()];

    $this->responseMock->method('withJson')
      ->willReturnArgument(0);
    $this->mock->expects($this->once())
      ->method('findEntity')
      ->willReturn($app);
    $this->requestMock->method('getParsedBody')
      ->willReturn(['hash' => 'adasdasdae3e342e3r']);
    $this->mock->expects($this->once())
      ->method('getPartners')
      ->willReturn($partnerArray);
    $this->mock->expects($this->once())
      ->method('serializeObjects')
      ->willReturn(['asdad','adasd']);
    $this->offerRepoMock->method('getOffersByApplication')
      ->willReturn([new Offer, new Offer]);
    $this->mock->expects($this->once())
      ->method('getOfferRepository')
      ->willReturn($this->offerRepoMock);

    $result = $this->mock->statusAction($this->requestMock, $this->responseMock, []);
    $this->assertArrayHasKey('status', $result);
    $this->assertSame('done', $result['status']);
  }

  public function testStatusActionWaiting()
  {
    $app = new Application();
    $app->setOffers([new Offer]);
    $partnerArray = [new Partner(), new Partner()];

    $this->responseMock->method('withJson')
      ->willReturnArgument(0);
    $this->mock->expects($this->once())
      ->method('findEntity')
      ->willReturn($app);
    $this->requestMock->method('getParsedBody')
      ->willReturn(['hash' => 'adasdasdae3e342e3r']);
    $this->mock->expects($this->once())
      ->method('getPartners')
      ->willReturn($partnerArray);
    $this->mock->expects($this->once())
      ->method('serializeObjects')
      ->willReturn(['asdad','adasd']);
    $this->offerRepoMock->method('getOffersByApplication')
      ->willReturn([new Offer]);
    $this->mock->expects($this->once())
      ->method('getOfferRepository')
      ->willReturn($this->offerRepoMock);

    $result = $this->mock->statusAction($this->requestMock, $this->responseMock, []);
    $this->assertArrayHasKey('status', $result);
    $this->assertSame('waiting', $result['status']);
  }
}
