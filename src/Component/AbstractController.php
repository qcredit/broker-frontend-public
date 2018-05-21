<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 10.04.18
 * Time: 10:38
 */

namespace App\Component;

use Slim\Container;
use Slim\Flash\Messages;
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
   * @var Container
   */
  protected $container;

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
   * @return Container
   * @codeCoverageIgnore
   */
  public function getContainer()
  {
    return $this->container;
  }

  /**
   * @param Container $container
   * @return AbstractController
   * @codeCoverageIgnore
   */
  public function setContainer(Container $container)
  {
    $this->container = $container;
    return $this;
  }

  /**
   * AbstractController constructor.
   * @param Container $container
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function __construct(Container $container)
  {
    $this->container = $container;
    $this->request = $container->get('request');
    $this->response = $container->get('response');
  }

  /**
   * @param Response $response
   * @param $viewFile
   * @param array $data
   * @return mixed
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function render(Response $response, $viewFile, $data = [])
  {
    $this->setResponse($response);
    $this->prepareFlashes($data);
    $this->getAdditionalData($data);

    return $this->getContainer()->get('view')->render($response, $viewFile, $data);
  }

  /**
   * @param array $data
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function prepareFlashes(array &$data)
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
   * @param array $data
   */
  protected function getAdditionalData(array &$data)
  {
    if ($this->getResponse()->hasHeader('X-Locale'))
    {
      $data['lang'] = $this->getResponse()->getHeader('X-Locale')[0];
    }
  }

  /**
   * @return array|null|object
   */
  protected function getParsedBody()
  {
    $body = $this->getRequest()->getParsedBody();
    unset($body['csrf_name']);
    unset($body['csrf_value']);

    return $body;
  }

  /**
   * @return Messages
   * @throws \Interop\Container\Exception\ContainerException
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