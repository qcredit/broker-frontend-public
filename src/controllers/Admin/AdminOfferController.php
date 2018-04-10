<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 09.04.18
 * Time: 16:15
 */

namespace App\Controllers\Admin;

use Broker\Domain\Interfaces\OfferRepositoryInterface;
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
   * @param OfferUpdateService $offerUpdateService
   * @return AdminOfferController
   */
  public function setOfferUpdateService($offerUpdateService)
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
   * @return static
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

    $offers = $this->getOfferUpdateService()->setOffers([$offer])->run();

    if (isset($offers[0]) && $offers[0]->getId() === $offer->getId())
    {
      $this->getContainer()->get('flash')->addMessage('success', 'Offer was successfully updated.');
    }
    else
    {
      $this->getContainer()->get('flash')->addMessage('error', 'Could not update the offer.');
    }

    return $response->withRedirect(sprintf('/admin/applications/%d', $offer->getApplicationId()));
  }
}