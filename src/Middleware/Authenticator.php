<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 12.04.18
 * Time: 13:21
 */

namespace App\Middleware;

use App\Base\Components\AuthHandler;
use Slim\App;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class Authenticator
{
  /**
   * @var App
   */
  protected $app;

  /**
   * @var Container
   */
  protected $container;

  /**
   * Authenticator constructor.
   * @param App $app
   */
  public function __construct(App $app)
  {
    $this->app = $app;
    $this->container = $app->getContainer();
  }

  /**
   * @return App
   */
  public function getApp()
  {
    return $this->app;
  }

  /**
   * @param App $app
   * @return Authenticator
   */
  public function setApp(App $app)
  {
    $this->app = $app;
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
   * @return Authenticator
   */
  public function setContainer(Container $container)
  {
    $this->container = $container;
    return $this;
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $next
   * @return Response
   * @throws \Psr\Container\ContainerExceptionInterface
   * @throws \Psr\Container\NotFoundExceptionInterface
   */
  public function __invoke(Request $request, Response $response, $next)
  {
    $container = $this->getContainer();
    $session = $container->get('session');

    if (!($session->exists(AuthHandler::SESSION_AUTH_KEY) && $session->exists(AuthHandler::SESSION_USER_ID)))
    {
      return $this->denyAccess($response);
    }

    $user = $this->getUser($session->get(AuthHandler::SESSION_USER_ID));

    if (!$user || !$user->validateAuthKey($session->get(AuthHandler::SESSION_AUTH_KEY)))
    {
      return $this->denyAccess($response);
    }

    $request = $request->withAttribute('user', $user);
    $response = $next($request, $response);

    return $response;
  }

  /**
   * @param Response $response
   * @return Response
   */
  protected function denyAccess(Response $response)
  {
    return $response->withRedirect('/');
  }

  /**
   * @param $id
   * @return mixed
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function getUser($id)
  {
    $container = $this->getContainer();

    $factory = $container->get('RepositoryFactory');
    $repository = $factory->createGateway($container->get('db'), 'User');

    return $repository->getOneBy(['id' => $id]);
  }
}