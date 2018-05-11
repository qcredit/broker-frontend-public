<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 12.04.18
 * Time: 15:41
 */

namespace App\Controller\Admin;

use App\Base\Components\AbstractController;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class AdminController extends AbstractController
{
  protected $container;

  /**
   * @return mixed
   */
  public function getContainer()
  {
    return $this->container;
  }

  /**
   * @param mixed $container
   * @return AdminController
   */
  public function setContainer($container)
  {
    $this->container = $container;
    return $this;
  }

  /**
   * AdminController constructor.
   * @param Container $container
   */
  public function __construct(Container $container)
  {
    $this->container = $container;
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return mixed
   */
  public function indexAction(Request $request, Response $response, $args)
  {
    $stats = $this->getApplicationStats();

    $factory = $this->getContainer()->get('RepositoryFactory');
    $repo = $factory->createGateway($this->getContainer()->get('db'), 'Partner');
    $data = ['user' => $request->getAttribute('user')];

    $data['partners'] = $repo->getPartnersWithStats();
    $data['stats'] = $stats;

    return $this->render($response, 'admin/index.twig', $data);
  }

  /**
   * @return array
   */
  protected function getApplicationStats()
  {
    $stats = [];
    $appRepository = $this->getContainer()->get('ApplicationRepository');
    $stats['month'] = $appRepository->getApplicationStats('-1 month');
    $stats['day'] = $appRepository->getApplicationStats('-1 day');
    $stats['week'] = $appRepository->getApplicationStats('-1 week');
    return $stats;
  }
}