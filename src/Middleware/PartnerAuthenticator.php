<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 10.05.18
 * Time: 09:53
 */

namespace App\Middleware;

use App\Base\Repository\PartnerExtraDataLoader;
use Broker\Domain\Interfaces\Repository\PartnerRepositoryInterface;
use Slim\App;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Broker\Domain\Entity\Partner;

class PartnerAuthenticator
{
  const AUTH_TYPE_BASIC = 'Basic';

  const ALLOWED_AUTH_TYPES = [
    self::AUTH_TYPE_BASIC
  ];

  /**
   * @var App
   */
  protected $app;

  /**
   * @var Container
   */
  protected $container;

  /**
   * @var Partner
   */
  protected $authorizedPartner;

  /**
   * @return App
   * @codeCoverageIgnore
   */
  public function getApp()
  {
    return $this->app;
  }

  /**
   * @param App $app
   * @return PartnerAuthenticator
   * @codeCoverageIgnore
   */
  public function setApp(App $app)
  {
    $this->app = $app;
    return $this;
  }

  /**
   * @return Container
   * @codeCoverageIgnore
   */
  public function getContainer()
  {
    return $this->container;
  }

  /**
   * @return PartnerRepositoryInterface
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getPartnerRepository()
  {
    return $this->getContainer()->get('PartnerRepository');
  }

  /**
   * @return PartnerExtraDataLoader
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getPartnerExtraDataLoader()
  {
    return $this->getContainer()->get('PartnerExtraDataLoader');
  }

  /**
   * @param Container $container
   * @return PartnerAuthenticator
   * @codeCoverageIgnore
   */
  public function setContainer(Container $container)
  {
    $this->container = $container;
    return $this;
  }

  /**
   * @return Partner
   * @codeCoverageIgnore
   */
  public function getAuthorizedPartner()
  {
    return $this->authorizedPartner;
  }

  /**
   * @param Partner $authorizedPartner
   * @return PartnerAuthenticator
   * @codeCoverageIgnore
   */
  public function setAuthorizedPartner(Partner $authorizedPartner)
  {
    $this->authorizedPartner = $authorizedPartner;
    return $this;
  }

  /**
   * Authenticator constructor.
   * @param App $app
   */
  public function __construct(App $app)
  {
    $this->app = $app;
    $this->container = $app->getContainer();
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $next
   * @return Response
   * @throws \Exception
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function __invoke(Request $request, Response $response, $next)
  {
    if (!$request->getHeader('HTTP_AUTHORIZATION'))
    {
      return $this->denyAccess($response, 'Missing authorization information!');
    }
    if (!$this->authorizeRequest($request->getHeader('HTTP_AUTHORIZATION')))
    {
      return $this->denyAccess($response, 'No access!');
    }

    $request = $request->withAttribute('partner', $this->getAuthorizedPartner());
    $response = $next($request, $response);

    return $response;
  }

  /**
   * @param Response $response
   * @param string $reason
   * @return Response
   */
  protected function denyAccess(Response $response, string $reason)
  {
    return $response->withStatus(401, $reason);
  }

  /**
   * @param array $requestHeader
   * @return bool
   * @throws \Exception
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function authorizeRequest(array $requestHeader)
  {
    foreach ($this->getPartners() as $partner)
    {
      if ($this->authorizePartner($partner, $requestHeader)) return true;
    }

    return false;
  }

  /**
   * @return array
   * @throws \Exception
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function getPartners()
  {
    return $this->getPartnerExtraDataLoader()->bulkLoadExtraConfiguration($this->getPartnerRepository()->getAll());
  }

  /**
   * @param string $header
   * @return array
   */
  protected function getAuthTypeAndKey(string $header)
  {
    return explode(' ', $header);
  }

  /**
   * @param Partner $partner
   * @param array $requestHeader
   * @return bool
   */
  protected function authorizePartner(Partner $partner, array $requestHeader)
  {
    $method = $this->identifyAuthMethod($requestHeader);

    if ($partner->getAttribute('authorization') === $method && $this->authorizePartnerMethod($partner, $requestHeader))
    {
      $this->setAuthorizedPartner($partner);
      return true;
    }

    return false;
  }

  /**
   * @param $requestHeader
   * @return bool|string
   */
  protected function identifyAuthMethod(array $requestHeader)
  {
    $parts = $this->getAuthTypeAndKey($requestHeader[0]);

    if (!empty($parts) && $parts[0] === self::AUTH_TYPE_BASIC)
    {
      return self::AUTH_TYPE_BASIC;
    }

    return false;
  }

  /**
   * @param Partner $partner
   * @param array $requestHeader
   * @return bool
   */
  protected function authorizePartnerMethod(Partner $partner, array $requestHeader)
  {
    if ($partner->getAttribute('authorization') == self::AUTH_TYPE_BASIC)
    {
      $typeAndKey = $this->getAuthTypeAndKey($requestHeader[0]);
      return base64_encode(sprintf('%s:%s', $partner->getAttribute('localUsername'), $partner->getAttribute('localPassword'))) === $typeAndKey[1];
    }

    return false;
  }
}