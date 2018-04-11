<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 02.04.18
 * Time: 12:42
 */

namespace App\controllers;

use Slim\Views\Twig;

class HomeController
{
  /**
   * @var \Twig
   */
  protected $view;

  public function __construct(Twig $view)
  {
    $this->view = $view;
  }

  /**
   * @param $request
   * @param $response
   * @return \Psr\Http\Message\ResponseInterface
   */
  public function indexAction($request, $response)
  {
    return $this->view->render($response, 'index.twig');
  }
}