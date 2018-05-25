<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 20.04.18
 * Time: 09:41
 */

namespace App\Base\Components;

use Broker\Domain\Entity\Message;
use Broker\Domain\Entity\PartnerRequest;
use Broker\Domain\Entity\PartnerResponse;
use Broker\Domain\Interfaces\MessageDeliveryInterface;
use Broker\System\Log;
use Monolog\Logger;
use Slim\Container;

class ApiDelivery implements MessageDeliveryInterface
{
  /**
   * @var HttpClient
   */
  protected $client;
  /**
   * @var Message
   */
  protected $message;
  /**
   * @var PartnerResponse
   */
  protected $response;
  /**
   * @var bool
   */
  protected $ok;
  /**
   * @var Container
   */
  protected $container;

  /**
   * @return HttpClient
   */
  public function getClient()
  {
    return $this->client;
  }

  /**
   * @param HttpClient $client
   * @return ApiDelivery
   */
  public function setClient(HttpClient $client)
  {
    $this->client = $client;
    return $this;
  }

  /**
   * @param Message $message
   * @return $this
   */
  public function setMessage(Message $message)
  {
    $this->message = $message;
    return $this;
  }

  /**
   * @return Message
   */
  public function getMessage(): Message
  {
    return $this->message;
  }

  /**
   * @return PartnerResponse
   */
  public function getResponse()
  {
    return $this->response;
  }

  /**
   * @param PartnerResponse $response
   * @return ApiDelivery
   */
  public function setResponse(PartnerResponse $response)
  {
    $this->response = $response;
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
   * @return Logger
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getLogger()
  {
    return $this->getContainer()->get('logger');
  }

  /**
   * ApiDelivery constructor.
   * @param Container $container
   */
  public function __construct(Container $container)
  {
    $this->container = $container;
    $this->setClient(new HttpClient());
    $this->setResponse(new PartnerResponse());
  }

  /**
   * @param Message $message
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function send(Message $message)
  {
    $this->setMessage($message);
    $this->setup();

    try {
      $result = $this->getClient()->send();
      $code = $this->getClient()->getStatusCode();
      $this->getResponse()->setResponseBody($result);

      if (preg_match('^(?:2)\d{2}$', $code))
      {
        $this->setOk(true);
      }
      else {
        $this->setOk(false);
        $this->getLogger()->error(sprintf('API request returned non-successful return code (code %s)!', $code), [$this->getClient()->getError(), json_decode($result, true)]);
      }
    }
    catch (\Exception $ex)
    {
      $this->getLogger()->critical('API request failed with exception!', [$ex->getMessage()]);
      $this->setOk(false);
    }

    $this->getResponse()->setOk($this->isOk());
  }

  protected function setup()
  {
    $this->setClientOptions();
    $request = $this->getMessage()->getRelatedEntity();
    $this->getResponse()->setPartner($request->getPartner())
      ->setType($request->getType());
  }

  protected function setClientOptions()
  {
    $request = $this->getMessage()->getRelatedEntity();
    $username = $request->getPartner()->getRemoteUsername();
    $password = $request->getPartner()->getRemotePassword();

    $headers = [
      'Accept: application/json',
      'Content-Type: application/json',
      sprintf('Authorization: Basic %s', base64_encode(sprintf('%s:%s', $username, $password)))
    ];

    $options = [];

    if ($request->getType() === PartnerRequest::REQUEST_TYPE_INITIAL)
    {
      $options[CURLOPT_URL] = $request->getPartner()->getApiTestUrl();
      $options[CURLOPT_POSTFIELDS] = $request->getRequestPayload();
    }
    else if ($request->getType() === PartnerRequest::REQUEST_TYPE_UPDATE)
    {
      $options[CURLOPT_URL] = $request->getPartner()->getApiTestUrl() . "/" . $request->getOffer()->getRemoteId();
      $headers[] = sprintf('X-Auth-Token: %s', $request->getOffer()->getDataElement('token'));
    }
    else if ($request->getType() === PartnerRequest::REQUEST_TYPE_CHOOSE)
    {
      $headers[] = sprintf('X-Auth-Token: %s', $request->getOffer()->getDataElement('token'));
      $options[CURLOPT_URL] = $request->getPartner()->getApiTestUrl() . "/" . $request->getOffer()->getRemoteId();
      $options[CURLOPT_POSTFIELDS] = $request->getRequestPayload();
      $options[CURLOPT_CUSTOMREQUEST] = 'PATCH';

      //@todo Move this line away from herrre!!!!11!1!11!!1
      $this->getResponse()->setOffer($request->getOffer());
    }

    $options[CURLOPT_RETURNTRANSFER] = true;
    $options[CURLOPT_SSL_VERIFYPEER] = false;
    $options[CURLOPT_CONNECTTIMEOUT] = 30;

    $this->getClient()->setClientOptions($options);
    $this->getClient()->setClientHeaders($headers);
  }

  /**
   * @param bool $value
   * @return $this
   */
  public function setOk(bool $value)
  {
    $this->ok = $value;
    return $this;
  }

  /**
   * @return bool
   */
  public function isOk(): bool
  {
    return $this->ok;
  }
}
