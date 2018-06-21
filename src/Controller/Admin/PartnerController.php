<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 05.04.18
 * Time: 15:55
 */

namespace App\Controller\Admin;

use App\Component\AbstractController;
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
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function __construct(PartnerRepositoryInterface $partnerRepository, PartnerFactoryInterface $partnerFactory, PartnerExtraDataLoader $partnerDataLoader, Container $container)
  {
    $this->partnerRepository = $partnerRepository;
    $this->partnerFactory = $partnerFactory;
    $this->partnerDataLoader = $partnerDataLoader;
    $this->validator = new PartnerValidator($container->get('BrokerInstance'));

    parent::__construct($container);
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
    $data = ['user' => $request->getAttribute('user')];

    $partners = $this->getPartnerRepository()->getAll();

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
    $data = ['user' => $request->getAttribute('user')];

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
    $data = ['user' => $request->getAttribute('user')];

    $data['partner'] = $this->findEntity($args['id'], $request, $response);

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
    $data = ['user' => $request->getAttribute('user')];

    $partner = $this->getPartnerFactory()->create();
    $partner->setValidator($this->getValidator());

    if ($request->isPost() && $partner->load($request->getParsedBody()) && $partner->validate())
    {
      if ($this->getPartnerRepository()->save($partner))
      {
        return $response->withRedirect('/office/partners');
      }
    }

    $data['partner'] = $partner;

    return $this->render($response, 'admin/partner-form.twig', $data);
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return Response
   * @throws \Slim\Exception\NotFoundException
   */
  public function deleteAction(Request $request, Response $response, $args)
  {
    $partner = $this->findEntity($args['id'], $request, $response);

    if ($this->getPartnerRepository()->delete($partner))
    {
      return $response->withRedirect('/office/partners');
    }

    return $this->withRedirect('/office/partners');
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