<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 10.04.18
 * Time: 10:38
 */

namespace App\Base\Components;

use Slim\Http\Request;
use Slim\Http\Response;

abstract class AbstractController
{
  /**
   * @var Request
   */
  protected $request;
  /**
   * @var Response
   */
  protected $response;

  /**
   * @return Request
   * @codeCoverageIgnore
   */
  public function getRequest()
  {
    return $this->request;
  }

  /**
   * @param Request $request
   * @return AbstractController
   * @codeCoverageIgnore
   */
  public function setRequest(Request $request)
  {
    $this->request = $request;
    return $this;
  }

  /**
   * @return Response
   * @codeCoverageIgnore
   */
  public function getResponse()
  {
    return $this->response;
  }

  /**
   * @param Response $response
   * @return AbstractController
   * @codeCoverageIgnore
   */
  public function setResponse(Response $response)
  {
    $this->response = $response;
    return $this;
  }

  /**
   * @param Response $response
   * @param $viewFile
   * @param array $data
   * @return mixed
   */
  public function render(Response $response, $viewFile, $data = [])
  {
    $this->setResponse($response);
    $this->prepareFlashes($data);
    $this->getAdditionalData($data);

    return $this->getContainer()->get('view')->render($response, $viewFile, $data);
  }

  /**
   * @param $data
   */
  public function prepareFlashes(&$data)
  {
    $flash = $this->getFlash();

    if ($flash->hasMessage('success'))
    {
      $data['flash']['success'] = $flash->getFirstMessage('success');
    }
    elseif ($flash->hasMessage('error'))
    {
      $data['flash']['error'] = $flash->getFirstMessage('error');
    }
  }

  /**
   * @param $data
   */
  protected function getAdditionalData(&$data)
  {
    if ($this->getResponse()->hasHeader('X-Locale'))
    {
      $data['lang'] = $this->getResponse()->getHeader('X-Locale')[0];
    }
  }

  /**
   * @return mixed
   */
  protected function getFlash()
  {
    return $this->getContainer()->get('flash');
  }

  /**
   * @param Request $request
   * @return bool
   */
  protected function isAjax(Request $request)
  {
    return $request->hasHeader('HTTP_X_REQUESTED_WITH');
  }
}