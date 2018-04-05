<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 05.04.18
 * Time: 15:55
 */

namespace App\Controllers\Admin;

use Broker\Domain\Interfaces\FactoryInterface;
use Broker\Domain\Interfaces\PartnerRepositoryInterface;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class PartnerController
{
  /**
   * @var PartnerRepositoryInterface
   */
  protected $partnerRepository;
  /**
   * @var FactoryInterface
   */
  protected $partnerFactory;
  /**
   * @var Container
   */
  protected $container;

  /**
   * PartnerController constructor.
   * @param PartnerRepositoryInterface $partnerRepository
   * @param FactoryInterface $partnerFactory
   * @param Container $container
   */
  public function __construct(PartnerRepositoryInterface $partnerRepository, FactoryInterface $partnerFactory, Container $container)
  {
    $this->partnerRepository = $partnerRepository;
    $this->partnerFactory = $partnerFactory;
    $this->container = $container;
  }

  /**
   * @return PartnerRepositoryInterface
   */
  public function getPartnerRepository()
  {
    return $this->partnerRepository;
  }

  /**
   * @param PartnerRepositoryInterface $partnerRepository
   * @return PartnerController
   */
  public function setPartnerRepository(PartnerRepositoryInterface $partnerRepository)
  {
    $this->partnerRepository = $partnerRepository;
    return $this;
  }

  /**
   * @return FactoryInterface
   */
  public function getPartnerFactory()
  {
    return $this->partnerFactory;
  }

  /**
   * @param FactoryInterface $partnerFactory
   * @return PartnerController
   */
  public function setPartnerFactory(FactoryInterface $partnerFactory)
  {
    $this->partnerFactory = $partnerFactory;
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
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return mixed
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function indexAction(Request $request, Response $response, $args)
  {
    $data = [];

    $partners = $this->getPartnerRepository()->getAll();

    $data['partners'] = $partners;

    return $this->getContainer()->get('view')->render($response, 'admin/partners.twig', $data);
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return mixed
   * @throws \Interop\Container\Exception\ContainerException
   * @throws \Slim\Exception\NotFoundException
   */
  public function updateAction(Request $request, Response $response, $args)
  {
    $data = [];

    $partner = $this->findEntity($args['id'], $request, $response);

    if ($request->isPost())
    {
      $partner->load($request->getParsedBody());
      $this->getPartnerRepository()->save($partner);
    }

    $data['partner'] = $partner;

    return $this->getContainer()->get('view')->render($response, 'admin/partner-form.twig', $data);
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return mixed
   * @throws \Interop\Container\Exception\ContainerException
   * @throws \Slim\Exception\NotFoundException
   */
  public function viewAction(Request $request, Response $response, $args)
  {
    $data = [];

    $data['partner'] = $this->findEntity($args['id'], $request, $response);

    return $this->getContainer()->get('view')->render($response, 'admin/partner.twig', $data);
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return mixed
   * @throws \Exception
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function newAction(Request $request, Response $response, $args)
  {
    $data = [];

    $partner = $this->getPartnerFactory()->create();

    if ($request->isPost())
    {
      $partner->load($request->getParsedBody());
      $this->getPartnerRepository()->save($partner);
      return $response->withRedirect('/admin/partners');
    }

    $data['partner'] = $partner;

    return $this->getContainer()->get('view')->render($response, 'admin/partner-form.twig', $data);
  }

  /**
   * @param $id
   * @param $request
   * @param $response
   * @return mixed
   * @throws \Slim\Exception\NotFoundException
   */
  protected function findEntity($id, $request, $response)
  {
    $partner = $this->getPartnerRepository()->getOneBy(['id' => $id]);

    if (!$partner)
    {
      throw new \Slim\Exception\NotFoundException($request, $response);
    }

    return $partner;
  }
}