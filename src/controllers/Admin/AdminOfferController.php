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

  public function __construct(OfferUpdateService $offerUpdateService, OfferRepositoryInterface $offerRepository)
  {
    $this->offerUpdateService = $offerUpdateService;
    $this->offerRepository = $offerRepository;
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return static
   * @throws NotFoundException
   * @throws \Exception
   */
  public function updateAction(Request $request, Response $response, $args)
  {
    $offer = $this->getOfferRepository()->getOneBy(['id' => $args['id']]);

    if (!$offer)
    {
      throw new NotFoundException($request, $response);
    }

    $this->getOfferUpdateService()->setOffers([$offer])->run();

    return $response->withRedirect(sprintf('/admin/applications/%d', $offer->getApplicationId()));
  }
}