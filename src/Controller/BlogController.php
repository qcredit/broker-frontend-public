<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 16.05.18
 * Time: 17:03
 */

namespace App\Controller;

use Aasa\CommonWebSDK\BlogServiceAWS;
use App\Component\AbstractController;
use Slim\Container;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class BlogController extends AbstractController
{
  /**
   * @var BlogServiceAWS
   */
  private $blogService;
  /**
   * @var Container
   */
  private $container;

  /**
   * @return BlogServiceAWS
   */
  public function getBlogService()
  {
    return $this->blogService;
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
   * BlogController constructor.
   * @param BlogServiceAWS $blogService
   * @param Container $container
   */
  public function __construct(BlogServiceAWS $blogService, Container $container)
  {
    $this->blogService = $blogService;
    $this->container = $container;
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param array $args
   * @return mixed
   */
  public function indexAction(Request $request, Response $response, $args = [])
  {
    $data = [];
    $data['posts'] = $this->getBlogService()->select(0, 20);

    return $this->render($response, 'blog/index.twig', $data);
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return mixed
   * @throws NotFoundException
   */
  public function viewAction(Request $request, Response $response, $args)
  {
    $data = [];
    $data['post'] = $this->getBlogService()->selectByUrl($args['slug']);

    if (!$data['post'])
    {
      throw new NotFoundException($request, $response);
    }

    return $this->render($response, 'blog/view.twig', $data);
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return mixed
   */
  public function tagAction(Request $request, Response $response, $args)
  {
    $data = [];
    $data['posts'] = $this->getBlogService()->selectByTag($args['tag']);

    return $this->render($response, 'blog/tag.twig', $data);
  }
}