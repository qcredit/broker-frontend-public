<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 02.04.18
 * Time: 12:42
 */

namespace App\Controller;

use App\Component\AbstractController;
use App\Middleware\LanguageSwitcher;
use Slim\Container;
use Slim\Http\Cookies;
use Slim\Http\Request;
use Slim\Http\Response;
use SlimSession\Helper;

class HomeController extends AbstractController
{
  /**
   * @var Container
   */
  protected $container;

  /**
   * @return Container
   * @codeCoverageIgnore
   */
  public function getContainer()
  {
    return $this->container;
  }

  /**
   * @param Container $container
   * @return HomeController
   * @codeCoverageIgnore
   */
  public function setContainer(Container $container)
  {
    $this->container = $container;
    return $this;
  }

  /**
   * @return Cookies
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getCookies()
  {
    return $this->getContainer()->get('cookies');
  }

  /**
   * @return Helper
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getSession()
  {
    return $this->getContainer()->get('session');
  }

  /**
   * HomeController constructor.
   * @param Container $container
   */
  public function __construct(Container $container)
  {
    $this->setContainer($container);
  }

  /**
   * @param $request
   * @param $response
   * @return \Psr\Http\Message\ResponseInterface
   */
  public function indexAction($request, $response)
  {
    return $this->render($response, 'index.twig');
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return Response
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function languageAction(Request $request, Response $response, $args)
  {
    $language = $request->getQueryParam('lang');
    if (strpos($language, '_') === false)
    {
      $language = sprintf('%s_%s', $language, strtoupper($language));
    }

    $this->getSession()->set(LanguageSwitcher::COOKIE_LANGUAGE, $language);

    return $response->withRedirect($this->getReferer());
  }

  /**
   * @return string
   */
  protected function getReferer()
  {
    return !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
  }
}