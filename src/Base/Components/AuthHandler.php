<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 11.04.18
 * Time: 17:11
 */

namespace App\Base\Components;

use App\Base\Interfaces\AuthenticationServiceInterface;
use App\Base\Interfaces\UserIdentityInterface;
use App\Base\Interfaces\UserRepositoryInterface;
use Slim\Container;

class AuthHandler
{
  const SESSION_AUTH_KEY = 'authKey';
  const SESSION_USER_ID = 'uID';
  /**
   * @var AuthenticationServiceInterface
   */
  protected $authService;
  /**
   * @var array
   */
  protected $payload;
  /**
   * @var UserRepositoryInterface
   */
  protected $userRepository;
  /**
   * @var UserIdentityInterface
   */
  protected $user;
  /**
   * @var string
   */
  protected $error;
  /**
   * @var Container
   */
  protected $container;

  /**
   * @return AuthenticationServiceInterface
   */
  public function getAuthService()
  {
    return $this->authService;
  }

  /**
   * @param AuthenticationServiceInterface $authService
   * @return AuthHandler
   */
  public function setAuthService(AuthenticationServiceInterface $authService)
  {
    $this->authService = $authService;
    return $this;
  }

  /**
   * @return array
   */
  public function getPayload()
  {
    return $this->payload;
  }

  /**
   * @param array $payload
   * @return AuthHandler
   */
  public function setPayload(array $payload)
  {
    $this->payload = $payload;
    return $this;
  }

  /**
   * @return UserIdentityInterface
   */
  public function getUser()
  {
    return $this->user;
  }

  /**
   * @param UserIdentityInterface $user
   * @return AuthHandler
   */
  public function setUser(UserIdentityInterface $user)
  {
    $this->user = $user;
    return $this;
  }

  /**
   * @return UserRepositoryInterface
   */
  public function getUserRepository()
  {
    return $this->userRepository;
  }

  /**
   * @param UserRepositoryInterface $userRepository
   * @return AuthHandler
   */
  public function setUserRepository(UserRepositoryInterface $userRepository)
  {
    $this->userRepository = $userRepository;
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
   * @return AuthHandler
   */
  public function setContainer(Container $container)
  {
    $this->container = $container;
    return $this;
  }

  /**
   * AuthHandler constructor.
   * @param AuthenticationServiceInterface $authenticationService
   * @param UserRepositoryInterface $userRepository
   * @param Container $container
   */
  public function __construct(
    AuthenticationServiceInterface $authenticationService,
    UserRepositoryInterface $userRepository,
    Container $container
  )
  {
    $this->authService = $authenticationService;
    $this->userRepository = $userRepository;
    $this->container = $container;
  }

  /**
   * @return bool
   * @throws \Exception
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function login()
  {
    $this->findUser();

    if (!$this->getUser())
    {
      $this->setError('No user found with given e-mail!');
      return false;
    }

    $service = $this->getAuthService()->setPayload($this->getPayload());
    if (!$service->authenticate())
    {
      $this->setError(sprintf('Could not authenticate with %s!', $service->getName()));
      return false;
    }

    $this->setupSession();

    return true;
  }

  /**
   * @return bool
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function logout()
  {
    $session = $this->getContainer()->get('session');
    $session->delete(self::SESSION_AUTH_KEY);
    $session->delete(self::SESSION_USER_ID);

    return true;
  }

  /**
   * @throws \Exception
   */
  protected function findUser()
  {
    $payload = $this->getPayload();
    if (!isset($payload['email']))
    {
      throw new \Exception('No e-mail given!');
    }

    $this->setUser($this->getUserRepository()->getByEmail($payload['email']));
  }

  /**
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function setupSession()
  {
    $user = $this->getUser();
    $user->generateAuthKey();

    $session = $this->getContainer()->get('session');
    $session->set(self::SESSION_AUTH_KEY, $user->getAuthKey());
    $session->set(self::SESSION_USER_ID, $user->getId());

    $this->getUserRepository()->save($user);
  }

  /**
   * @return string
   */
  public function getError()
  {
    return $this->error;
  }

  /**
   * @param string $error
   */
  public function setError(string $error)
  {
    $this->error = $error;
  }
}