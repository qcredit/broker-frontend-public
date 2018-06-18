<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 14.06.18
 * Time: 08:09
 */

namespace App\Middleware;


use Slim\App;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class ApplicationPost
{
  /**
   * @var App
   */
  protected $app;
  /**
   * @var Container
   */
  protected $container;

  public function __construct(App $app)
  {
    $this->app = $app;
    $this->container = $app->getContainer();
  }

  /**
   * @return App
   * @codeCoverageIgnore
   */
  public function getApp()
  {
    return $this->app;
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
   * @return array
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getSettings()
  {
    return $this->getContainer()->get('settings');
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $next
   * @return Response|static
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function __invoke(Request $request, Response $response, $next)
  {
    $referer = $request->getHeader('REFERER');

    $settings = $this->getSettings();
    $domain = $settings['baseUrl'] ?? 'qcredit';

    if ($settings['broker']['environment'] == 'production' && (!(is_array($referer) && strpos($referer[0], $domain) !== false) || !$request->isPost()))
    {
      return $response->withRedirect('/');
    }

    $response = $next($request, $response);

    return $response;
  }
}