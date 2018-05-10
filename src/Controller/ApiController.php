<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 08.05.18
 * Time: 10:01
 */

namespace App\Controller;

use Broker\Domain\Entity\Partner;
use Broker\Domain\Entity\PartnerResponse;
use Broker\Domain\Interfaces\Factory\PartnerResponseFactoryInterface;
use App\Base\Persistence\Doctrine\PartnerRepository;
use Broker\Domain\Interfaces\Service\PartnerUpdateServiceInterface;
use Monolog\Logger;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class ApiController
{
  /**
   * @var PartnerUpdateServiceInterface
   */
  protected $partnerUpdateService;
  /**
   * @var PartnerResponseFactoryInterface
   */
  protected $partnerResponseFactory;
  /**
   * @var Container
   */
  protected $container;

  /**
   * @return PartnerUpdateServiceInterface
   * @codeCoverageIgnore
   */
  public function getPartnerUpdateService()
  {
    return $this->partnerUpdateService;
  }

  /**
   * @param PartnerUpdateServiceInterface $partnerUpdateService
   * @return ApiController
   * @codeCoverageIgnore
   */
  public function setPartnerUpdateService(PartnerUpdateServiceInterface $partnerUpdateService)
  {
    $this->partnerUpdateService = $partnerUpdateService;
    return $this;
  }

  /**
   * @return PartnerResponseFactoryInterface
   * @codeCoverageIgnore
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getPartnerResponseFactory()
  {
    return $this->getContainer()->get('PartnerResponseFactory');
  }

  /**
   * @param PartnerResponseFactoryInterface $partnerResponseFactory
   * @return ApiController
   * @codeCoverageIgnore
   */
  public function setPartnerResponseFactory(PartnerResponseFactoryInterface $partnerResponseFactory)
  {
    $this->partnerResponseFactory = $partnerResponseFactory;
    return $this;
  }

  /**
   * @return Container
   * @codeCoverageIgnore
   */
  public function getContainer()
  {
    return $this->container;
  }

  /**
   * @param Container $container
   * @return ApiController
   * @codeCoverageIgnore
   */
  public function setContainer(Container $container)
  {
    $this->container = $container;
    return $this;
  }

  /**
   * @return Logger
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getLogger()
  {
    return $this->getContainer()->get('logger');
  }

  /**
   * ApiController constructor.
   * @param PartnerUpdateServiceInterface $partnerUpdateService
   * @param Container $container
   */
  public function __construct(
    PartnerUpdateServiceInterface $partnerUpdateService,
    Container $container
  )
  {
    $this->setPartnerUpdateService($partnerUpdateService);
    $this->setContainer($container);
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return Response
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function updateAction(Request $request, Response $response, $args)
  {
    $data = $request->getParsedBody() ?? [];
    $this->getLogger()->debug('Incoming Aasa update request...');

    $partnerResponse = $this->preparePartnerResponse($data, $request->getAttribute('partner'));

    $service = $this->getPartnerUpdateService();
    $service->setPartnerResponse($partnerResponse)->run();

    return $response->withJson($service->getResponseForPartner());
  }

  /**
   * @param array $data
   * @param Partner $partner
   * @return PartnerResponse
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function preparePartnerResponse(array $data, Partner $partner)
  {
    $partnerResponse = $this->getPartnerResponseFactory()->create();
    $partnerResponse->setType(PartnerResponse::TYPE_EXTERNAL_UPDATE)
      ->setResponseBody(json_encode($data))
      ->setPartner($partner);

    return $partnerResponse;
  }

}