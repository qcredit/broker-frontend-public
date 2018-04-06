<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 06.04.18
 * Time: 17:02
 */

namespace App\Controllers\Admin;


use Broker\Domain\Interfaces\ApplicationRepositoryInterface;
use Broker\Persistence\Doctrine\ApplicationRepository;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class AdminApplicationController
{
  /**
   * @var ApplicationRepositoryInterface
   */
  protected $appRepository;
  /**
   * @var Container
   */
  protected $container;

  /**
   * AdminApplicationController constructor.
   * @param ApplicationRepositoryInterface $appRepository
   * @param Container $container
   */
  public function __construct(ApplicationRepositoryInterface $appRepository, Container $container)
  {
    $this->appRepository = $appRepository;
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

    return $this->getContainer()->get('view')->render($response, 'admin/applications.twig', $data);
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return mixed
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function viewAction(Request $request, Response $response, $args)
  {
    $data = [];

    $data['application'] = $this->getAppRepository()->getOneBy(['id' => $args['id']]);

    return $this->getContainer()->get('view')->render($response, 'admin/application.twig', $data);
  }
}