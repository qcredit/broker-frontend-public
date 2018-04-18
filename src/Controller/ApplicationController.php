<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 19.03.18
 * Time: 16:58
 */

namespace App\Controller;

use App\Base\Components\AbstractController;
use Broker\Domain\Interfaces\Repository\ApplicationRepositoryInterface;
use Broker\Domain\Interfaces\Repository\OfferRepositoryInterface;
use Broker\Domain\Interfaces\Service\ChooseOfferServiceInterface;
use Broker\Domain\Interfaces\Service\PreparePartnerRequestsServiceInterface;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Exception\NotFoundException;

class ApplicationController extends AbstractController
{
  /**
   * @var PreparePartnerRequestsServiceInterface
   */
  protected $prepareService;
  /**
   * @var Container
   */
  protected $container;
  /**
   * @var ApplicationRepositoryInterface
   */
  protected $appRepository;
  /**
   * @var OfferRepositoryInterface
   */
  protected $offerRepository;
  /**
   * @var ChooseOfferServiceInterface
   */
  protected $chooseOfferService;

  /**
   * ApplicationController constructor.
   * @param PreparePartnerRequestsServiceInterface $prepareService
   * @param ApplicationRepositoryInterface $appRepository
   * @param OfferRepositoryInterface $offerRepository
   * @param ChooseOfferServiceInterface $chooseOfferService
   * @param $container
   */
  public function __construct(
    PreparePartnerRequestsServiceInterface $prepareService,
    ApplicationRepositoryInterface $appRepository,
    OfferRepositoryInterface $offerRepository,
    ChooseOfferServiceInterface $chooseOfferService,
    $container)
  {
    $this->prepareService = $prepareService;
    $this->appRepository = $appRepository;
    $this->container = $container;
    $this->offerRepository = $offerRepository;
    $this->chooseOfferService = $chooseOfferService;
  }

  /**
   * @return PreparePartnerRequestsServiceInterface
   */
  public function getPrepareService()
  {
    return $this->prepareService;
  }

  /**
   * @param PreparePartnerRequestsServiceInterface $prepareService
   * @return $this
   */
  public function setPrepareService(PreparePartnerRequestsServiceInterface $prepareService)
  {
    $this->prepareService = $prepareService;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getContainer()
  {
    return $this->container;
  }

  /**
   * @param mixed $container
   * @return ApplicationController
   */
  public function setContainer(Container $container)
  {
    $this->container = $container;
    return $this;
  }

  /**
   * @return ApplicationRepositoryInterface
   */
  public function getAppRepository()
  {
    return $this->appRepository;
  }

  /**
   * @param ApplicationRepositoryInterface $appRepository
   * @return $this
   */
  public function setAppRepository(ApplicationRepositoryInterface $appRepository)
  {
    $this->appRepository = $appRepository;
    return $this;
  }

  /**
   * @return OfferRepositoryInterface
   */
  public function getOfferRepository()
  {
    return $this->offerRepository;
  }

  /**
   * @param OfferRepositoryInterface $offerRepository
   * @return ApplicationController
   */
  public function setOfferRepository(OfferRepositoryInterface $offerRepository)
  {
    $this->offerRepository = $offerRepository;
    return $this;
  }

  /**
   * @return ChooseOfferServiceInterface
   */
  public function getChooseOfferService()
  {
    return $this->chooseOfferService;
  }

  /**
   * @param ChooseOfferServiceInterface $chooseOfferService
   * @return ApplicationController
   */
  public function setChooseOfferService(ChooseOfferServiceInterface $chooseOfferService)
  {
    $this->chooseOfferService = $chooseOfferService;
    return $this;
  }

  /**
   * @param $request
   * @param $response
   * @param $args
   * @return mixed
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function indexAction($request, $response, $args)
  {

    $data = [
      'incomeSourceType' => 'Employed',
      'netPerMonth' => 4400,
      'employerName' => 'European Parliament',
      'position' => 'Cleaning man',
      'yearSince' => 2013,
      'monthSince' => 1,
      'currentStudy' => '',
      'trade' => 'unknown',
      'loanPurposeType' => 'Bills',
      'pin' => '71121513234',
      'street' => 'Wolności',
      'zip' => '44-285',
      'houseNr' => '60',
      'apartmentNr' => ' ',
      'city' => 'Kornowac',
      'documentNr' => 'CCY014054',
      'mobilePhoneType' => 'PrePaid',
      'payoutMethod' => 'Account',
      'educationType' => 'MSc',
      'accountType' => 'Personal',
      'accountNr' => '32132345678901234569415123',
      'accountHolder' => 'Anu Saagim',
      'maritalStatusType' => 'Single',
      'residentialType' => 'Own',
      'propertyType' => 'Apartment',
      'loanAmount' => 2100,
      'loanTerm' => 18,
      'firstName' => 'Adam',
      'lastName' => 'Barański',
      'email' => 'brak@asakredyt.pl',
      'phone' => '+48739050381'
    ];

    //$data = [];
    if ($request->isPost())
    {
      $app = $this->prepareService->setData($request->getParsedBody())->run();
      $data['errors'] = $app->getErrors();
      $data['application'] = $app;

      if (!isset($data['errors']) || empty($data['errors']))
      {
        return $response->withRedirect(sprintf('application/%s', $app->getApplicationHash()));
      }
    }

    return $this->container->get('view')->render($response, 'application/form.twig', $data);
  }

  /**
   * @param $request
   * @param $response
   * @param $args
   * @return mixed
   * @throws \Exception
   */
  public function offersAction(Request $request, Response $response, $args)
  {
    $application = $this->findEntity($args['hash'], $request, $response);

    $data = [
      'application' => $application
    ];

    return $this->render($response, 'application/offer-list.twig', $data);
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return mixed
   * @throws NotFoundException
   */
  public function selectOfferAction(Request $request, Response $response, $args)
  {
    $data = [];
    $data['application'] = $this->findEntity($args['hash'], $request, $response);
    $data['offer'] = $this->getOfferRepository()->getActiveOfferById($args['id']);

    if (!$data['offer'])
    {
      throw new NotFoundException($request, $response);
    }

    if ($request->isPost())
    {
      $service = $this->getChooseOfferService();
      $service->setData($request->getParsedBody())->setOffer($data['offer']);

      if (!$service->run())
      {
        $data['offer'] = $service->getOffer();
      }
      else
      {
        return $response->withRedirect('/application/thankyou');
      }
    }

    return $this->render($response, 'application/choose-offer.twig', $data);
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return mixed
   */
  public function thankYouAction(Request $request, Response $response, $args)
  {
    $data = [];
    return $this->render($response, 'application/thankyou.twig', $data);
  }

  /**
   * @param $hash
   * @param $request
   * @param $response
   * @return null|object
   * @throws \Slim\Exception\NotFoundException
   */
  protected function findEntity($hash, Request $request, Response $response)
  {
    $application = $this->getAppRepository()->getByHash($hash);

    if (!$application)
    {
      throw new NotFoundException($request, $response);
    }

    return $application;
  }
}
