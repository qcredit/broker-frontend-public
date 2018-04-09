<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 19.03.18
 * Time: 16:58
 */

namespace App\controllers;

use Broker\Domain\Service\ApplicationOfferListService;
use Broker\Domain\Service\PreparePartnerRequestsService;
use Broker\Persistence\Doctrine\ApplicationRepository;

class ApplicationController
{
  protected $prepareService;
  protected $container;
  protected $appRepository;

  /**
   * ApplicationController constructor.
   * @param PreparePartnerRequestsService $prepareService
   * @param ApplicationRepository $appRepository
   * @param $container
   */
  public function __construct(
    PreparePartnerRequestsService $prepareService,
    ApplicationRepository $appRepository,
    $container)
  {
    $this->prepareService = $prepareService;
    $this->appRepository = $appRepository;
    $this->container = $container;
  }

  /**
   * @return PreparePartnerRequestsService
   */
  public function getPrepareService()
  {
    return $this->prepareService;
  }

  /**
   * @param PreparePartnerRequestsService $prepareService
   * @return ApplicationController
   */
  public function setPrepareService($prepareService)
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
  public function setContainer($container)
  {
    $this->container = $container;
    return $this;
  }

  /**
   * @return ApplicationRepository
   */
  public function getAppRepository()
  {
    return $this->appRepository;
  }

  /**
   * @param ApplicationRepository $appRepository
   * @return ApplicationController
   */
  public function setAppRepository($appRepository)
  {
    $this->appRepository = $appRepository;
    return $this;
  }

  /**
   * @param $request
   * @param $response
   * @param $args
   * @return \Psr\Http\Message\ResponseInterface
   * @throws \Exception
   */
  public function indexAction($request, $response, $args)
  {

/*    $data = [
      'incomeSourceType' => 'Employed',
      'netPerMonth' => 3200,
      'employerName' => 'Wasras asd',
      'position' => 'Wont tell',
      'yearSince' => 2006,
      'monthSince' => 06,
      'currentStudy' => 'TÜ',
      'trade' => 'unknown',
      'loanPurposeType' => 'Car',
      'pin' => '92090700966',
      'street' => 'Koidu',
      'zip' => '66-121',
      'houseNr' => '20',
      'apartmentNr' => '1',
      'city' => 'Warsawa',
      'documentNr' => 'AUH611715',
      'mobilePhoneType' => 'PrePaid',
      'payoutMethod' => 'Account',
      'educationType' => 'MSc',
      'accountType' => 'Personal',
      'accountNr' => '11232345678901234569415835',
      'accountHolder' => 'Koit Toome',
      'maritalStatusType' => 'Single',
      'residentialType' => 'Own',
      'propertyType' => 'House',
      'loanAmount' => 8000,
      'loanTerm' => 24,
      'firstName' => 'Koit',
      'lastName' => 'Toome',
      'email' => 'koit@toome.ee',
      'phone' => '5171231'
    ];*/

    $data = [];
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
  public function offersAction($request, $response, $args)
  {
    $application = $this->getAppRepository()->getByHash($args['hash']);

    if (!$application)
    {
      throw new \Slim\Exception\NotFoundException($request, $response);
    }

    $data = [
      'application' => $application
    ];

    return $this->getContainer()->get('view')->render($response, 'application/offer-list.twig', $data);
  }
}