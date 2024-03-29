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
use App\Component\FormBuilder;
use App\Controller\ApplicationController;
use Broker\Domain\Service\PostApplicationService;
use Broker\System\BaseTest;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Container;
use Slim\Views\Twig;
use App\Base\Persistence\Doctrine\ApplicationRepository;
use Broker\Domain\Entity\Application;
use Broker\Domain\Entity\Offer;
use Broker\Domain\Entity\Partner;
use Tests\Helpers\LoggerMockTrait;

class ApplicationControllerTest extends BaseTest
{
  use LoggerMockTrait;

  protected $mock;
  protected $requestMock;
  protected $responseMock;
  protected $containerMock;
  protected $repositoryMock;
  protected $offerRepoMock;
  protected $messageTemplateRepoMock;
  protected $serviceMock;
  protected $formBuilderMock;

  public function setUp()
  {
    $this->setupMocks();
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
        'getMessageTemplateRepository',
        'getFormBuilder',
        'getPostApplicationService',
        'getParsedBody',
        'getLogger'
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
      ->setMethods(['getAll', 'getOneBy', 'getByHash', 'getOffersByApplication', 'getBy', 'getAcceptedOffersByApplication', 'getPendingOffersByApplication'])
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

    $this->serviceMock = $this->getMockBuilder(PostApplicationService::class)
      ->disableOriginalConstructor()
      ->setMethods(['run', 'setValidationEnabled', 'getApplication'])
      ->getMock();

    $this->formBuilderMock = $this->createMock(FormBuilder::class);
  }

  public function testOfferListAction()
  {
    $mock = $this->mock;

    $mock->expects($this->once())
      ->method('findEntity')
      ->willReturn((new Application())->setId(2));

    $this->offerRepoMock->method('getBy')
      ->willReturn([]);
    $mock->method('getOfferRepository')
      ->willReturn($this->offerRepoMock);

    $this->mock->method('render')->willReturnArgument(2);

    $result = $mock->offersAction($this->requestMock, $this->responseMock, ['hash' => 'asdasd']);

    $this->assertTrue(is_array($result));
    $this->assertInstanceOf(Application::class, $result['application']);
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
      ->method('serializeObjects')
      ->willReturn(['asdad','adasd']);
    $this->offerRepoMock->method('getAcceptedOffersByApplication')
      ->willReturn([new Offer, new Offer]);
    $this->offerRepoMock->method('getPendingOffersByApplication')
      ->willReturn([]);
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

    $this->responseMock->method('withJson')
      ->willReturnArgument(0);
    $this->mock->expects($this->once())
      ->method('findEntity')
      ->willReturn($app);
    $this->requestMock->method('getParsedBody')
      ->willReturn(['hash' => 'adasdasdae3e342e3r']);
    $this->mock->expects($this->once())
      ->method('serializeObjects')
      ->willReturn(['asdad','adasd']);
    $this->offerRepoMock->method('getAcceptedOffersByApplication')
      ->willReturn([new Offer]);
    $this->offerRepoMock->method('getPendingOffersByApplication')
      ->willReturn([new Offer]);
    $this->mock->expects($this->once())
      ->method('getOfferRepository')
      ->willReturn($this->offerRepoMock);

    $result = $this->mock->statusAction($this->requestMock, $this->responseMock, []);
    $this->assertArrayHasKey('status', $result);
    $this->assertSame('waiting', $result['status']);
  }

  public function testIndexActionWithExceptionThrown()
  {
    $this->requestMock->method('isPost')
      ->willReturn(true);
    $this->serviceMock->method('setValidationEnabled')
      ->willThrowException(new \Exception());
    $this->mock->method('getFormBuilder')
      ->willReturn($this->formBuilderMock);
    $this->mock->method('getParsedBody')
      ->willReturn([]);
    $this->mock->method('getPostApplicationService')
      ->willReturn($this->serviceMock);
    $this->mock->method('getLogger')
      ->willReturn($this->loggerMock);

    $this->expectException(\Exception::class);

    $this->invokeMethod($this->mock, 'indexAction', [$this->requestMock, $this->responseMock, []]);
  }
}
