<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 09.04.18
 * Time: 16:15
 */

namespace App\Controller\Admin;

use Broker\Domain\Interfaces\Repository\OfferRepositoryInterface;
use Broker\Domain\Interfaces\Service\OfferUpdateServiceInterface;
use Broker\Domain\Service\OfferUpdateService;
use Slim\Container;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class AdminOfferController
{
  /**
   * @var OfferUpdateService
   */
  protected $offerUpdateService;
  /**
   * @var OfferRepositoryInterface
   */
  protected $offerRepository;
  /**
   * @var Container
   */
  protected $container;

  /**
   * @return OfferUpdateService
   */
  public function getOfferUpdateService()
  {
    return $this->offerUpdateService;
  }

  /**
   * @param OfferUpdateServiceInterface $offerUpdateService
   * @return $this
   */
  public function setOfferUpdateService(OfferUpdateServiceInterface $offerUpdateService)
  {
    $this->offerUpdateService = $offerUpdateService;
    return $this;
  }

  /**
   * @return OfferRepositoryInterface
   */
  public function getOfferRepository()
  {
    return $this->offerRepository;
  }

  /**
   * @param OfferRepositoryInterface $offerRepository
   * @return AdminOfferController
   */
  public function setOfferRepository($offerRepository)
  {
    $this->offerRepository = $offerRepository;
    return $this;
  }

  /**
   * @return Container
   */
  public function getContainer()
  {
    return $this->container;
  }

  /**
   * @param Container $container
   * @return AdminOfferController
   */
  public function setContainer($container)
  {
    $this->container = $container;
    return $this;
  }

  public function __construct(OfferUpdateService $offerUpdateService, OfferRepositoryInterface $offerRepository, Container $container)
  {
    $this->offerUpdateService = $offerUpdateService;
    $this->offerRepository = $offerRepository;
    $this->container = $container;
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return Response
   * @throws NotFoundException
   * @throws \Exception
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function updateAction(Request $request, Response $response, $args)
  {
    $offer = $this->getOfferRepository()->getOneBy(['id' => $args['id']]);

    if (!$offer)
    {
      throw new NotFoundException($request, $response);
    }

    $this->getOfferUpdateService()->setOffers([$offer])->run();
    $offers = $this->getOfferUpdateService()->getOffers();

    if (isset($offers[0]) && $offers[0]->getId() === $offer->getId())
    {
      $this->getContainer()->get('flash')->addMessage('success', 'Offer was successfully updated.');
    }
    else
    {
      $this->getContainer()->get('flash')->addMessage('error', 'Could not update the offer. Probably the token has expired...');
    }

    return $response->withRedirect(sprintf('/admin/applications/%d', $offer->getApplicationId()));
  }
}