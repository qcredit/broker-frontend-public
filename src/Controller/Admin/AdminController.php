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
    $factory = $this->getContainer()->get('RepositoryFactory');
    $repo = $factory->createGateway($this->getContainer()->get('db'), 'Partner');
    $data = ['user' => $request->getAttribute('user')];

    $data['partners'] = $repo->getPartnersWithStats();

    return $this->render($response, 'admin/index.twig', $data);
  }
}