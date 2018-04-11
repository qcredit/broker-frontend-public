<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 06.04.18
 * Time: 17:02
 */

namespace App\Controller\Admin;

use App\Base\Components\AbstractController;
use Broker\Domain\Interfaces\Repository\ApplicationRepositoryInterface;
use Broker\Domain\Interfaces\Repository\OfferRepositoryInterface;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class AdminApplicationController extends AbstractController
{
  /**
   * @var ApplicationRepositoryInterface
   */
  protected $appRepository;
  /**
   * @var OfferRepositoryInterface
   */
  protected $offerRepository;
  /**
   * @var Container
   */
  protected $container;

  /**
   * AdminApplicationController constructor.
   * @param ApplicationRepositoryInterface $appRepository
   * @param OfferRepositoryInterface $offerRepository
   * @param Container $container
   */
  public function __construct(ApplicationRepositoryInterface $appRepository,
    OfferRepositoryInterface $offerRepository,
    Container $container)
  {
    $this->appRepository = $appRepository;
    $this->offerRepository = $offerRepository;
    $this->container = $container;
  }

  /**
   * @return ApplicationRepositoryInterface
   */
  public function getAppRepository()
  {
    return $this->appRepository;
  }

  /**
   * @param ApplicationRepositoryInterface $appRepository
   * @return AdminApplicationController
   */
  public function setAppRepository($appRepository)
  {
    $this->appRepository = $appRepository;
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
   * @return AdminApplicationController
   */
  public function setOfferRepository(OfferRepositoryInterface $offerRepository)
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
   * @return AdminApplicationController
   */
  public function setContainer($container)
  {
    $this->container = $container;
    return $this;
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return mixed
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function indexAction(Request $request, Response $response, $args)
  {
    $data = [];

    $data['applications'] = $this->getAppRepository()->getAll();

    return $this->render($response, 'admin/applications.twig', $data);
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return mixed
   * @throws \Interop\Container\Exception\ContainerException
   * @throws \Slim\Exception\NotFoundException
   */
  public function viewAction(Request $request, Response $response, $args)
  {
    $data = [];

    $data['application'] = $this->findEntity($args['id'], $request, $response);

    return $this->render($response, 'admin/application.twig', $data);
  }

  /**
   * @param $id
   * @param $request
   * @param $response
   * @return mixed
   * @throws \Slim\Exception\NotFoundException
   */
  protected function findEntity($id, $request, $response)
  {
    $entity = $this->getAppRepository()->getOneBy(['id' => $id]);

    if (!$entity)
    {
      throw new \Slim\Exception\NotFoundException($request, $response);
    }

    return $entity;
  }
}