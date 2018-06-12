<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 19.03.18
 * Time: 16:58
 */

namespace App\Controller;

use App\Base\Event\PostDataListener;
use App\Base\NewAppListener;
use App\Component\AbstractController;
use Broker\Domain\Interfaces\Repository\MessageTemplateRepositoryInterface;
use App\Model\ApplicationForm;
use Broker\Domain\Interfaces\Repository\ApplicationRepositoryInterface;
use Broker\Domain\Interfaces\Repository\OfferRepositoryInterface;
use Broker\Domain\Interfaces\Service\ChooseOfferServiceInterface;
use Broker\Domain\Interfaces\Service\NewApplicationServiceInterface;
use Broker\Domain\Interfaces\Service\PostApplicationServiceInterface;
use Broker\Domain\Interfaces\Service\PrepareAndSendApplicationServiceInterface;
use Broker\Domain\Interfaces\Service\PreparePartnerRequestsServiceInterface;
use Broker\Domain\Interfaces\Service\SendPartnerRequestsServiceInterface;
use Broker\Domain\Interfaces\System\Event\EventListenerInterface;
use Broker\Domain\Service\PreparePartnerRequestsService;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Exception\NotFoundException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use App\Component\SchemaHelper;

class ApplicationController extends AbstractController
{
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
   * @var PostApplicationServiceInterface
   */
  protected $postApplicationService;

  /**
   * ApplicationController constructor.
   * @param ApplicationRepositoryInterface $appRepository
   * @param OfferRepositoryInterface $offerRepository
   * @param ChooseOfferServiceInterface $chooseOfferService
   * @param PostApplicationServiceInterface $postApplicationService
   * @param $container
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function __construct(
    ApplicationRepositoryInterface $appRepository,
    OfferRepositoryInterface $offerRepository,
    ChooseOfferServiceInterface $chooseOfferService,
    PostApplicationServiceInterface $postApplicationService,
    $container
  )
  {
    $this->appRepository = $appRepository;
    $this->offerRepository = $offerRepository;
    $this->chooseOfferService = $chooseOfferService;
    $this->postApplicationService = $postApplicationService;

    parent::__construct($container);
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
   * @return PostApplicationServiceInterface
   */
  public function getPostApplicationService()
  {
    return $this->postApplicationService;
  }

  /**
   * @return array
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function getPartnersDataMappers()
  {
    $result = [];
    foreach ($this->getPartners() as $partner)
    {
      if ($partner->hasDataMapper())
      {
        $result[] = $partner->getDataMapper();
      }
    }

    return $result;
  }

  /**
   * @return mixed
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function getPartners()
  {
    return $this->getContainer()->get('PartnerRepository')->getActivePartners();
  }

  /**
   * @return bool
   */
  protected function isFromFrontpage()
  {
    return !strpos($_SERVER['HTTP_REFERER'], $_SERVER['REQUEST_URI']);
  }

  /**
   * @return mixed
   * @throws \Interop\Container\Exception\ContainerException
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

    $postData = $this->getParsedBody();

    $service = $this->getPostApplicationService()->setValidationEnabled(false);

    if ($request->isPost())
    {
      $service->setValidationEnabled(true);
      $service->setPostData($postData);
    }

    if ($request->isPost())
    {
      if ($this->isFromFrontpage())
      {
        $service->getNewApplicationService()->getApplicationValidator()->setValidationAttributes([ApplicationForm::ATTR_PHONE, ApplicationForm::ATTR_EMAIL]);
      }

      if ($this->isAjax($request))
      {
        $service->getNewApplicationService()->getApplicationValidator()->setValidationAttributes([ApplicationForm::ATTR_PIN, ApplicationForm::ATTR_EMAIL, ApplicationForm::ATTR_PHONE]);
      }
    }

    $service->run();

    if ($request->isPost() && $this->isAjax($request))
    {
      return $response->withJson(['applicationHash' => $service->getApplication()->getApplicationHash()]);
    }

    if ($request->isPost() && !$this->isAjax($request) && $this->getPostApplicationService()->isSuccess())
    {
      return $response->withRedirect(sprintf('application/%s', $this->getPostApplicationService()->getApplication()->getApplicationHash()));
    }

    $data['application'] = $service->getApplication();

    return $this->render($response, 'application/form.twig', $data);
  }

  /**
   * @param $request
   * @param $response
   * @param $args
   * @return mixed
   * @throws \Exception
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function offersAction(Request $request, Response $response, $args)
  {
    $application = $this->findEntity($args['hash'], $request, $response);
    $offers = $this->getOfferRepository()->getBy(['applicationId' => $application->getId(), 'rejectedDate' => null]);

    $data = [
      'application' => $application,
      'offers' => $offers
    ];

    return $this->render($response, 'application/offer-list.twig', $data);
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
   * @throws \Interop\Container\Exception\ContainerException
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
   * @throws \Exception
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function schemaAction(Request $request, Response $response, $args)
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
