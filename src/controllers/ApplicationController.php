<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 19.03.18
 * Time: 16:58
 */

namespace App\Controllers;

use Broker\Domain\Service\ApplicationOfferListService;
use Broker\Domain\Service\PreparePartnerRequestsService;

class ApplicationController
{
  protected $prepareService;
  protected $container;
  protected $offerListService;

  /**
   * ApplicationController constructor.
   * @param PreparePartnerRequestsService $prepareService
   * @param ApplicationOfferListService $offerListService
   * @param $container
   */
  public function __construct(
    PreparePartnerRequestsService $prepareService,
    ApplicationOfferListService $offerListService,
    $container)
  {
    $this->prepareService = $prepareService;
    $this->offerListService = $offerListService;
    $this->container = $container;
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
  public function offerListAction($request, $response, $args)
  {
    $offers = $this->offerListService
      ->setApplicationHash($args['hash'])
      ->run();

    if (!$offers)
    {
      throw new \Exception('No application was found!');
    }

    $data = [
      'offers' => $offers,
      'application' => $this->offerListService->getApplication()
    ];

    print_r($data);

    return $this->container->get('view')->render($response, 'application/offer-list.twig', $data);
  }
}