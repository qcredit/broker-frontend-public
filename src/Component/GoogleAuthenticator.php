<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 12.04.18
 * Time: 11:56
 */

namespace App\Component;

use App\Interfaces\AuthenticationServiceInterface;
use Firebase\JWT\JWT;

class GoogleAuthenticator implements AuthenticationServiceInterface
{
  const SERVICE_NAME = 'Google';
  const CLIENT_ID = '876809952895-5hg5tv7pj9d0f2bt3chijj2j68ttmi5m.apps.googleusercontent.com';

  /**
   * @var array
   */
  protected $payload;
  /**
   * @var
   */
  protected $client;

  /**
   * @param array $payload
   * @return $this
   */
  public function setPayload(array $payload)
  {
    $this->payload = $payload;
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
   * @return mixed
   */
  public function getClient()
  {
    return $this->client;
  }

  /**
   * GoogleAuthenticator constructor.
   */
  public function __construct()
  {
    JWT::$leeway = 10;
    $this->client = new \Google_Client(['client_id' => self::CLIENT_ID]);
  }

  /**
   * @return bool
   */
  public function authenticate(): bool
  {
    $client = $this->getClient();
    $payload = $this->getPayload();

    $response = $client->verifyIdToken($payload['idToken']);

    if (!$response)
    {
      return false;
    }

    return true;
  }

  /**
   * @return string
   */
  public function getName(): string
  {
    return self::SERVICE_NAME;
  }
}