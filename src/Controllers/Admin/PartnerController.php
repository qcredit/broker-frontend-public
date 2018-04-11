<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 05.04.18
 * Time: 15:55
 */

namespace App\Controllers\Admin;

use App\Base\Components\AbstractController;
use App\Base\Repository\PartnerExtraDataLoader;
use App\Base\Validator\PartnerValidator;
use Broker\Domain\Interfaces\Factory\PartnerFactoryInterface;
use Broker\Domain\Interfaces\Repository\PartnerRepositoryInterface;
use Broker\Domain\Service\Validator\AbstractEntityValidator;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class PartnerController extends AbstractController
{
  /**
   * @var PartnerRepositoryInterface
   */
  protected $partnerRepository;
  /**
   * @var PartnerFactoryInterface
   */
  protected $partnerFactory;
  /**
   * @var Container
   */
  protected $container;
  /**
   * @var AbstractEntityValidator
   */
  protected $validator;
  /**
   * @var PartnerExtraDataLoader
   */
  protected $partnerDataLoader;

  /**
   * PartnerController constructor.
   * @param PartnerRepositoryInterface $partnerRepository
   * @param PartnerFactoryInterface $partnerFactory
   * @param PartnerExtraDataLoader $partnerDataLoader
   * @param Container $container
   */
  public function __construct(PartnerRepositoryInterface $partnerRepository, PartnerFactoryInterface $partnerFactory, PartnerExtraDataLoader $partnerDataLoader, Container $container)
  {
    $this->partnerRepository = $partnerRepository;
    $this->partnerFactory = $partnerFactory;
    $this->container = $container;
    $this->partnerDataLoader = $partnerDataLoader;
    $this->validator = new PartnerValidator();
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
   * @return PartnerFactoryInterface
   */
  public function getPartnerFactory()
  {
    return $this->partnerFactory;
  }

  /**
   * @param PartnerFactoryInterface $partnerFactory
   * @return $this
   */
  public function setPartnerFactory(PartnerFactoryInterface $partnerFactory)
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
   * @return AbstractEntityValidator
   */
  public function getValidator()
  {
    return $this->validator;
  }

  /**
   * @return PartnerExtraDataLoader
   */
  public function getPartnerDataLoader()
  {
    return $this->partnerDataLoader;
  }

  /**
   * @param PartnerExtraDataLoader $partnerDataLoader
   * @return PartnerController
   */
  public function setPartnerDataLoader($partnerDataLoader)
  {
    $this->partnerDataLoader = $partnerDataLoader;
    return $this;
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return mixed
   * @throws \Exception
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function indexAction(Request $request, Response $response, $args)
  {
    $data = [];

    $partners = $this->getPartnerDataLoader()->bulkLoadExtraConfiguration($this->getPartnerRepository()->getAll());

    $data['partners'] = $partners;

    return $this->render($response, 'admin/partners.twig', $data);
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
    $partner->setValidator($this->getValidator());

    if ($request->isPost())
    {
      if ($partner->load($request->getParsedBody()) && $partner->validate())
      {
        $this->getPartnerRepository()->save($partner);
      }
    }

    $data['partner'] = $partner;

    return $this->render($response, 'admin/partner-form.twig', $data);
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return mixed
   * @throws \Exception
   * @throws \Interop\Container\Exception\ContainerException
   * @throws \Slim\Exception\NotFoundException
   */
  public function viewAction(Request $request, Response $response, $args)
  {
    $data = [];

    $data['partner'] = $this->getPartnerDataLoader()->loadExtraConfiguration($this->findEntity($args['id'], $request, $response));

    return $this->render($response, 'admin/partner.twig', $data);
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
    $partner->setValidator($this->getValidator());

    if ($request->isPost())
    {
      if ($partner->load($request->getParsedBody()) && $partner->validate())
      {
        $this->getPartnerRepository()->save($partner);
        return $response->withRedirect('/admin/partners');
      }
    }

    $data['partner'] = $partner;

    return $this->render($response, 'admin/partner-form.twig', $data);
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