<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 19.03.18
 * Time: 16:58
 */

namespace App\Controller;

use App\Base\Components\AbstractController;
use App\Component\FormBuilder;
use Broker\Domain\Entity\Message;
use Broker\Domain\Interfaces\Repository\ApplicationRepositoryInterface;
use Broker\Domain\Interfaces\Repository\OfferRepositoryInterface;
use Broker\Domain\Interfaces\Repository\PartnerDataMapperRepositoryInterface;
use Broker\Domain\Interfaces\Service\ChooseOfferServiceInterface;
use Broker\Domain\Interfaces\Service\NewApplicationServiceInterface;
use Broker\Domain\Interfaces\Service\PreparePartnerRequestsServiceInterface;
use Broker\System\Helper;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Exception\NotFoundException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use App\Base\Components\SchemaHelper;

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
   * @return array
   */
  protected function getPartnersDataMappers()
  {
    $result = [];
    foreach ($this->getPartners() as $partner)
    {
      $result[] = $this->getPartnerDataMapperRepository()->getDataMapperByPartnerId($partner->getIdentifier());
    }

    return $result;
  }

  /**
   * @return array
   */
  protected function getPartners()
  {
    return $this->getContainer()->get('PartnerRepository')->getActivePartners();
  }

  /**
   * @return PartnerDataMapperRepositoryInterface
   */
  protected function getPartnerDataMapperRepository()
  {
    return $this->getContainer()->get('PartnerDataMapperRepository');
  }

  /**
   * @return bool
   */
  protected function isFromFrontpage()
  {
    return !strpos($_SERVER['HTTP_REFERER'], $_SERVER['REQUEST_URI']);
  }

  /**
   * @return FormBuilder
   */
  protected function getFormBuilder()
  {
    return $this->getContainer()->get('FormBuilder');
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

    $data = [];
    $data['fields'] = $this->getFormBuilder()->getFormFields();
    if ($request->isPost())
    {
      $newAppService = $this->getNewApplicationService();
      if ($this->isFromFrontpage())
      {
        $newAppService->setValidationEnabled(false);
      }

      $postData = $request->getParsedBody();
      unset($data['csrf_name']);
      unset($data['csrf_value']);

      if ($newAppService->setData($postData)->run())
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

      if ($this->isAjax($request))
      {
        $newAppService->saveApp();
        return $response->withJson(['applicationHash' => $newAppService->getApplication()->getApplicationHash()]);
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

  /**
   * @todo Move to MessageTemplateRepository
   */
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

  /**
   * @todo Move to MessageTemplateRepository
   */
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

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return Response
   * @throws NotFoundException
   */
  public function statusAction(Request $request, Response $response, $args)
  {
    $parts = explode('/', $_SERVER['HTTP_REFERER']);
    $app = $this->findEntity(end($parts), $request, $response);
    $partners = $this->getPartners();
    $offers = $this->getOfferRepository()->getOffersByApplication($app);

    $filtered = $this->serializeObjects($offers);

    if (count($partners) === count($offers))
    {
      return $response->withJson([
        'status' => 'done',
        'offers' => $filtered
      ]);
    }
    else {
      return $response->withJson([
        'status' => 'waiting',
        'offers' => $offers
      ]);
    }
  }

  /**
   * @param array $objects
   * @return array
   */
  protected function serializeObjects(array $objects)
  {
    $filtered = [];
    $encoders = [new JsonEncoder()];
    $normalizer = new ObjectNormalizer();
    $normalizer->setCircularReferenceLimit(1);
    $normalizer->setCircularReferenceHandler(function ($object) { return $object->getId(); });
    $serializer = new Serializer([$normalizer], $encoders);

    foreach ($objects as $object)
    {
      $object->setCreatedAt($object->getCreatedAt() ? $object->getCreatedAt()->format('Y-m-d H:i:s') : null);
      $object->setAcceptedDate($object->getAcceptedDate() ? $object->getAcceptedDate()->format('Y-m-d H:i:s') : null);
      $object->setUpdatedAt($object->getUpdatedAt() ? $object->getUpdatedAt()->format('Y-m-d H:i:s') : null);
      if ($object->getApplication()->getCreatedAt() instanceof \DateTime)
      {
        $object->getApplication()->setCreatedAt($object->getApplication()->getCreatedAt()->format('Y-m-d H:i:s'));
      }
      $filtered[] = $serializer->serialize($object, 'json');
    }

    return $filtered;
  }

  /**
   * @param $request
   * @param Response $response
   * @param $args
   * @return Response
   */
  public function schemaAction($request, Response $response, $args)
  {
    $helper = new SchemaHelper();

    return $response->withJson($helper->mergePartnersSchemas($this->getPartnersDataMappers()));
  }
}
