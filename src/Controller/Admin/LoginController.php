<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 12.04.18
 * Time: 10:05
 */

namespace App\Controller\Admin;

use App\Base\Components\AbstractController;
use App\Base\Components\AuthHandler;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class LoginController extends AbstractController
{
  /**
   * @var Container
   */
  protected $container;
  /**
   * @var AuthHandler
   */
  protected $authHandler;

  /**
   * @return Container
   */
  public function getContainer()
  {
    return $this->container;
  }

  /**
   * @param Container $container
   * @return LoginController
   */
  public function setContainer(Container $container)
  {
    $this->container = $container;
    return $this;
  }

  /**
   * @return AuthHandler
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getAuthHandler()
  {
    return $this->authHandler;
  }

  /**
   * LoginController constructor.
   * @param Container $container
   */
  public function __construct(Container $container, AuthHandler $authHandler)
  {
    $this->container = $container;
    $this->authHandler = $authHandler;
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return mixed
   * @throws \Exception
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function loginAction(Request $request, Response $response, $args)
  {
    $data = [];

    if ($request->isPost())
    {
      $payload = $request->getParsedBody();

      $handler = $this->getAuthHandler();
      $handler->setPayload($payload);

      if (!$handler->login())
      {
        return $response->withJson(['error' => $handler->getError()]);
      }

      return $response->withJson(['success' => true]);
    }

    return $this->render($response, 'admin/login.twig', $data);
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return Response
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function logoutAction(Request $request, Response $response, $args)
  {
    $handler = $this->getAuthHandler();

    if ($handler->logout())
    {
      return $response->withRedirect('/');
    }

    return $response->withRedirect($request->getHeader('HTTP_REFERER'));
  }
}