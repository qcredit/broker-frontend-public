<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 04.05.18
 * Time: 12:48
 */

namespace App\Controller;

use App\Component\AbstractController;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class TestController extends AbstractController
{
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
   * @return TestController
   * @codeCoverageIgnore
   */
  public function setContainer($container)
  {
    $this->container = $container;
    return $this;
  }

  public function __construct(Container $container)
  {
    $this->container = $container;
  }

  public function mailAction(Request $request, Response $response, $args)
  {
    $data = [];
    $data['offer'] = $this->getContainer()->get('OfferRepository')->getOneBy(['id' => 8]);
    $data['application'] = $this->getContainer()->get('ApplicationRepository')->getOneBy(['id' => 10]);
    $data['link'] = 'http://localhost:8100/application/34239048230482349023488234';

    $template = $request->getQueryParam('template');
    return $this->render($response, sprintf('mail/%s.twig', $template), $data);
  }
}