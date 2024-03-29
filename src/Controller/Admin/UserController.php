<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 11.04.18
 * Time: 09:41
 */

namespace App\Controller\Admin;

use App\Component\AbstractController;
use App\Base\Interfaces\UserFactoryInterface;
use App\Base\Interfaces\UserRepositoryInterface;
use Broker\Domain\Service\Validator\AbstractEntityValidator;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Model\UserForm;

class UserController extends AbstractController
{
  /**
   * @var UserRepositoryInterface
   */
  protected $userRepository;
  /**
   * @var UserFactoryInterface
   */
  protected $userFactory;
  /**
   * @var AbstractEntityValidator
   */
  protected $validator;
  /**
   * @var Container
   */
  protected $container;

  /**
   * @return UserRepositoryInterface
   */
  public function getUserRepository()
  {
    return $this->userRepository;
  }

  /**
   * @param UserRepositoryInterface $userRepository
   * @return UserController
   */
  public function setUserRepository($userRepository)
  {
    $this->userRepository = $userRepository;
    return $this;
  }

  /**
   * @return UserFactoryInterface
   */
  public function getUserFactory()
  {
    return $this->userFactory;
  }

  /**
   * @param UserFactoryInterface $userFactory
   * @return UserController
   */
  public function setUserFactory($userFactory)
  {
    $this->userFactory = $userFactory;
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
   * @param Container $container
   * @return UserController
   */
  public function setContainer($container)
  {
    $this->container = $container;
    return $this;
  }

  /**
   * @return AbstractEntityValidator
   */
  public function getValidator()
  {
    return $this->validator;
  }

  /**
   * @param AbstractEntityValidator $validator
   * @return UserController
   */
  public function setValidator(AbstractEntityValidator $validator)
  {
    $this->validator = $validator;
    return $this;
  }

  /**
   * UserController constructor.
   * @param UserRepositoryInterface $userRepository
   * @param UserFactoryInterface $userFactory
   * @param AbstractEntityValidator $validator
   * @param Container $container
   */
  public function __construct(
    UserRepositoryInterface $userRepository,
    UserFactoryInterface $userFactory,
    AbstractEntityValidator $validator,
    Container $container
  )
  {
    $this->userRepository = $userRepository;
    $this->userFactory = $userFactory;
    $this->validator = $validator;
    $this->container = $container;
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return mixed
   */
  public function indexAction(Request $request, Response $response, $args)
  {
    $data = ['user' => $request->getAttribute('user')];

    $data['users'] = $this->getUserRepository()->getAll();

    return $this->render($response, 'admin/users.twig', $data);
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return mixed|static
   * @throws \Broker\System\Error\InvalidConfigException
   * @throws \Exception
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function newAction(Request $request, Response $response, $args)
  {
    $data = ['user' => $request->getAttribute('user')];

    $user = $this->getUserFactory()->create();
    $user->setValidator($this->getValidator());

    if ($request->isPost())
    {
      if ($user->load($request->getParsedBody()) && $user->validate())
      {
        $this->getContainer()->get('flash')->addMessage('success', 'User was successfully added.');
        $this->getUserRepository()->save($user);
        return $response->withRedirect('/office/users');
      }
    }

    $data['user'] = $user;
    $data['accessLevelList'] = UserForm::ACCESS_LVL_LIST;

    return $this->render($response, 'admin/user-form.twig', $data);
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $args
   * @return static
   * @throws \Slim\Exception\NotFoundException
   */
  public function deleteAction(Request $request, Response $response, $args)
  {
    $user = $this->findEntity($args['id'], $request, $response);

    if ($this->getUserRepository()->delete($user))
    {
      return $response->withRedirect('/office/users');
    }

    return $this->withRedirect('/office/users');
  }

  public function loginAction(Request $request, Response $response, $args)
  {
    $data = ['user' => $request->getAttribute('user')];

    if ($request->isPost())
    {
      $payload = $request->getParsedBody();
      $user = $this->getUserRepository()->getByEmail($payload['email']);

      if (!$user)
      {
        return $response->withJson(['error' => 'No such user!']);
      }



      return $response->withJson();
    }

    return $this->render($response, 'admin/login.twig', $data);
  }

  protected function login($body)
  {
    $idToken = $body['idToken'];
    $clientId = '876809952895-st56us9uskma01899f546k6vobo9m2sn.apps.googleusercontent.com';

    $client = new \Google_Client(['client_id' => $clientId]);
    $payload = $client->verifyToken($idToken);
    if (!$payload)
    {
      return false;
    }

    $userId = $payload['sub'];

    return true;
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
    $entity = $this->getUserRepository()->getOneBy(['id' => $id]);

    if (!$entity)
    {
      throw new \Slim\Exception\NotFoundException($request, $response);
    }

    return $entity;
  }
}