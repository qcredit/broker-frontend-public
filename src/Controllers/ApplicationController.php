<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 19.03.18
 * Time: 16:58
 */

namespace App\controllers;

use Broker\Domain\Service\PreparePartnerRequestsService;
use App\Base\Persistence\Doctrine\ApplicationRepository;

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
      $app = $this->prepareService->setData($data)->run();
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