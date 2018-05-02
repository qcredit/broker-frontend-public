<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 19.03.18
 * Time: 16:58
 */

namespace App\Controller;

use App\Base\Components\AbstractController;
use App\Base\Components\EmailDelivery;
use Broker\Domain\Entity\Message;
use Broker\Domain\Interfaces\Repository\ApplicationRepositoryInterface;
use Broker\Domain\Interfaces\Repository\OfferRepositoryInterface;
use Broker\Domain\Interfaces\Service\ChooseOfferServiceInterface;
use Broker\Domain\Interfaces\Service\NewApplicationServiceInterface;
use Broker\Domain\Interfaces\Service\PreparePartnerRequestsServiceInterface;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Exception\NotFoundException;
use Broker\Domain\Entity\Application;

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
   * @var NewApplicationServiceInterface
   */
  protected $newApplicationService;

  /**
   * ApplicationController constructor.
   * @param PreparePartnerRequestsServiceInterface $prepareService
   * @param ApplicationRepositoryInterface $appRepository
   * @param OfferRepositoryInterface $offerRepository
   * @param ChooseOfferServiceInterface $chooseOfferService
   * @param NewApplicationServiceInterface $newApplicationService
   * @param $container
   */
  public function __construct(
    PreparePartnerRequestsServiceInterface $prepareService,
    ApplicationRepositoryInterface $appRepository,
    OfferRepositoryInterface $offerRepository,
    ChooseOfferServiceInterface $chooseOfferService,
    NewApplicationServiceInterface $newApplicationService,
    $container)
  {
    $this->prepareService = $prepareService;
    $this->appRepository = $appRepository;
    $this->container = $container;
    $this->offerRepository = $offerRepository;
    $this->chooseOfferService = $chooseOfferService;
    $this->newApplicationService = $newApplicationService;
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
   * @return NewApplicationServiceInterface
   */
  public function getNewApplicationService()
  {
    return $this->newApplicationService;
  }

  /**
   * @param NewApplicationServiceInterface $newApplicationService
   * @return ApplicationController
   */
  public function setNewApplicationService(NewApplicationServiceInterface $newApplicationService)
  {
    $this->newApplicationService = $newApplicationService;
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
      'email' => 'hendrik.uibopuu@aasaglobal.com',
      'phone' => '+48739050381'
    ];

    //$data = [];
    if ($request->isPost())
    {
      $newAppService = $this->getNewApplicationService();
      if ($this->isFromFrontpage())
      {
        $newAppService->setValidationEnabled(false);
      }

      if ($newAppService->setData($request->getParsedBody())->run())
      {
        $this->getPrepareService()->setApplication($newAppService->getApplication())
          ->setData($newAppService->getPreparedData());

        $this->generateOfferLinkMessage();

        if ($this->getPrepareService()->run())
        {
          //$newAppService->saveApp();
          return $response->withRedirect(sprintf('application/%s', $newAppService->getApplication()->getApplicationHash()));
        }
      }

      $data['application'] = $newAppService->getApplication();
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
    $data['offer'] = $this->getOfferRepository()->getOneBy(['id' => $args['id'], 'rejectedDate' => null, 'chosenDate' => null]);

    if (!$data['offer'])
    {
      throw new NotFoundException($request, $response);
    }

    if ($request->isPost())
    {
      $service = $this->getChooseOfferService()
        ->setData($request->getParsedBody())->setOffer($data['offer']);

      $this->generateOfferConfirmationMessage();

      if ($service->run())
      {
        return $this->render($response, 'application/thankyou.twig', $data);
      }

      $data['offer'] = $service->getOffer();
    }

    return $this->render($response, 'application/choose-offer.twig', $data);
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

  protected function generateOfferConfirmationMessage()
  {
    $offer = $this->getChooseOfferService()->getOffer();
    $message = new Message();
    $message->setTitle('Thank you for choosing us!')
      ->setRecipient($offer->getApplication()->getEmail())
      ->setType(Message::MESSAGE_TYPE_EMAIL)
      ->setBody($this->generateEmailContent('mail/offer-confirmation.twig', [
        'offer' => $offer,
        'title' => 'Your selected offer'
      ]));

    $this->getChooseOfferService()->getMessageDeliveryService()->setMessage($message);
  }

  protected function generateOfferLinkMessage()
  {
    $application = $this->getPrepareService()->getApplication();
    $message = new Message();
    $message->setTitle('Offers for your application')
      ->setType(Message::MESSAGE_TYPE_EMAIL)
      ->setBody($this->generateEmailContent('mail/offer-link.twig', [
        'application' => $application,
        'title' => 'Our offers for your application',
        'link' => sprintf('http://localhost:8100/application/%s', $application->getApplicationHash())
      ]))
      ->setRecipient($application->getEmail());

    $this->getPrepareService()->getMessageDeliveryService()->setMessage($message);
  }

  /**
   * @param $template
   * @param $params
   * @return mixed
   */
  protected function generateEmailContent($template, $params)
  {
    $twig = $this->getContainer()->get('view');

    return $twig->fetch($template, $params);
  }

  public function schemaAction($request, Response $response, $args)
  {
    $aasa = <<<JSON
{
    "type": "object",
    "required": [
      "loanAmount",
      "loanTerm",
      "firstName",
      "lastName",
      "payoutMethod",
      "documentNr",
      "phone",
      "educationType",
      "pin",
      "email",
      "maritalStatusType",
      "incomeSourceType",
      "netPerMonth",
      "street",
      "houseNr",
      "zip",
      "city",
      "accountNr",
      "accountType",
      "accountHolder",
      "residentialType",
      "propertyType"
    ],
    "properties": {
      "incomeSourceType": {
        "$ref": "#/definitions/sourceTypes"
      },
      "netPerMonth": {
        "type": "number",
        "minimum": 100
      },
      "pin": {
        "type": "string",
        "maxLength": 11,
        "pattern": "[0-9]{4}[0-3]{1}[0-9}{1}[0-9]{5}"
      },
      "street": {
        "type": "string",
        "maxLength": 100,
        "minLength": 2,
        "example": "Ul.brzozowskiego 12 m. 15"
      },
      "zip": {
        "type": "string",
        "example": "62-262",
        "pattern": "^[0-9]{2}-[0-9]{3}$"
      },
      "houseNr": {
        "type": "string",
        "maxLength": 10,
        "minLength": 1,
        "example": "1"
      },
      "apartmentNumber": {
        "type": "string",
        "maxLength": 10,
        "example": "1"
      },
      "city": {
        "type": "string",
        "maxLength": 50,
        "minLength": 2,
        "example": "Zachowice"
      },
      "accountNr": {
        "type": "string",
        "maxLength": 26,
        "minLength": 26,
        "example": "61 1090 1014 0000 0712 1981 2874"
      },
      "accountType": {
        "type": "string",
        "enum": [
          "-",
          "Personal",
          "Joint",
          "Company"
        ]
      },
      "accountHolder": {
        "type": "string",
        "example": "Anders Aas",
        "minLength": 1
      },
      "documentNr": {
        "type": "string",
        "example": "ZZC900146",
        "documentNr": "poland"
      },
      "payoutMethod": {
        "type": "string",
        "enum": [
          "Giro",
          "Account",
          "BlueCash"
        ]
      },
      "educationType": {
        "type": "string",
        "enum": [
          "MBA",
          "MSc",
          "BA",
          "Secondary",
          "Vocational",
          "Basic",
          "Other"
        ]
      },
      "maritalStatusType": {
        "type": "string",
        "enum": [
          "Single",
          "Married",
          "MarriedDivorcing",
          "Divorced",
          "Separated",
          "Widow",
          "InformationRelationship",
          "Other"
        ]
      },
      "residentialType": {
        "type": "string",
        "enum": [
          "Own",
          "Rented",
          "LivingWithFamily",
          "Other",
          "CouncilHousing",
          "HousingAssociation"
        ]
      },
      "propertyType": {
        "type": "string",
        "enum": [
          "Apartment",
          "House",
          "TerracedHouse",
          "Duplex",
          "Other"
        ]
      },
      "loanAmount": {
        "type": "number",
        "minimum": 10,
        "example": "1000.00"
      },
      "loanTerm": {
        "type": "number",
        "minimum": 1,
        "example": "12"
      },
      "firstName": {
        "type": "string",
        "maxLength": 50,
        "minLength": 1,
        "example": "Anders"
      },
      "lastName": {
        "type": "string",
        "maxLength": 50,
        "minLength": 1,
        "example": "Aas"
      },
      "email": {
        "type": "string",
        "example": "test.test@aasaglobal.com",
        "pattern": "\\\\S+@\\\\S+\\\\.\\\\S+"
      },
      "phone": {
        "type": "string",
        "example": "+372987654321",
        "pattern": "^(?:\\\\(?\\\\+?48)?(?:[-\\\\.\\\\(\\\\)\\\\s]*(\\\\d)){9}\\\\)?$"
      }
    },
    "definitions": {
      "sourceTypes": {
        "enum": [
          "Employed",
          "Student",
          "NormalPension",
          "DisabilityPension",
          "Unemployed",
          "BenefitOrAlimony",
          "SelfEmployed",
          "Farmer",
          "Other"
        ]
      }
    }
  }
JSON;

    $combined = [
      'allOf' => [
        json_decode($aasa)
      ]
    ];

    return $response->withJson($combined);
  }

  /**
   * @return bool
   */
  protected function isFromFrontpage()
  {
    return !strpos($_SERVER['HTTP_REFERER'], $_SERVER['REQUEST_URI']);
  }
}
