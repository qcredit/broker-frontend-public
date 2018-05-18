<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 19.03.18
 * Time: 16:58
 */

namespace App\Controller;

use App\Component\AbstractController;
use App\Base\Interfaces\MessageTemplateRepositoryInterface;
use App\Component\FormBuilder;
use App\Model\ApplicationForm;
use Broker\Domain\Interfaces\Repository\ApplicationRepositoryInterface;
use Broker\Domain\Interfaces\Repository\OfferRepositoryInterface;
use Broker\Domain\Interfaces\Repository\PartnerDataMapperRepositoryInterface;
use Broker\Domain\Interfaces\Service\ChooseOfferServiceInterface;
use Broker\Domain\Interfaces\Service\NewApplicationServiceInterface;
use Broker\Domain\Interfaces\Service\PreparePartnerRequestsServiceInterface;
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
   * @return MessageTemplateRepositoryInterface
   */
  public function getMessageTemplateRepository()
  {
    return $this->getContainer()->get('MessageTemplateRepository');
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
   * @todo Should allow validation according to scenarios
   */
  public function indexAction(Request $request, Response $response, $args)
  {
    $data = [];
    $data['fields'] = $this->getFormBuilder()->getFormFields();
    $newAppService = $this->getNewApplicationService();

    if ($request->isPost())
    {
      $postData = $request->getParsedBody();
      if ($this->isFromFrontpage() || $this->isAjax($request))
      {
        $newAppService->setValidationEnabled(false);
      }

      unset($postData['csrf_name']);
      unset($postData['csrf_value']);

      if ($newAppService->setData($postData)->run())
      {
        $this->getPrepareService()->setApplication($newAppService->getApplication())
          ->setData($newAppService->getPreparedData());

        $offerMessage = $this->getMessageTemplateRepository()->getOfferLinkMessage($this->getPrepareService()->getApplication());
        $this->getPrepareService()->getMessageDeliveryService()->setMessage($offerMessage);

        if ($this->getPrepareService()->run())
        {
          $newAppService->saveApp();
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
    else {
      $newAppService->setValidationEnabled(false);
      $newAppService->run();
      $data['application'] = $newAppService->getApplication();
    }

    return $this->render($response, 'application/form.twig', $data);
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

      $message = $this->getMessageTemplateRepository()->getOfferConfirmationMessage($this->getChooseOfferService()->getOffer());
      $this->getChooseOfferService()->getMessageDeliveryService()->setMessage($message);

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
    $form = new ApplicationForm();
    $errors = $form->getAjvErrors();

    return $response->withJson([
      'schema' => $helper->mergePartnersSchemas($this->getPartnersDataMappers()),
      'messages' => $errors
    ]);
  }
}
