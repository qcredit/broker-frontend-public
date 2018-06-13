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
use App\Component\Pagination;
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
   * @return BlogServiceAWS
   */
  public function getBlogService()
  {
    return $this->blogService;
  }

  /**
   * BlogController constructor.
   * @param BlogServiceAWS $blogService
   * @param Container $container
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function __construct(BlogServiceAWS $blogService, Container $container)
  {
    $this->blogService = $blogService;

    parent::__construct($container);
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param array $args
   * @return mixed
   * @throws \Interop\Container\Exception\ContainerException
   * @throws \Psr\SimpleCache\InvalidArgumentException
   */
  public function indexAction(Request $request, Response $response, $args = [])
  {
    $data = [];
    $pagination = new Pagination($request, $this->getBlogService()->getCount(), 10);
    $data['pagination'] = $pagination;
    $data['posts'] = $this->getBlogService()->select($pagination->getOffset(), ($pagination->getLimit() + $pagination->getOffset()));

    return $this->render($response, 'blog/index.twig', $data);
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return mixed
   * @throws NotFoundException
   * @throws \Interop\Container\Exception\ContainerException
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
   * @throws \Interop\Container\Exception\ContainerException
   * @throws \Psr\SimpleCache\InvalidArgumentException
   */
  public function tagAction(Request $request, Response $response, $args)
  {
    $data = [];
    $data['posts'] = $this->getBlogService()->selectByTag($args['tag']);
    $data['tag'] = $args['tag'];

    return $this->render($response, 'blog/tag.twig', $data);
  }
}