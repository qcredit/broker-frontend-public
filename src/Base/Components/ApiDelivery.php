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
   * ApiDelivery constructor.
   */
  public function __construct()
  {
    $this->setClient(new HttpClient());
    $this->setResponse(new PartnerResponse());
  }

  /**
   * @param Message $message
   * @throws \Exception
   */
  public function send(Message $message)
  {
    $this->setMessage($message);
    $this->setup();

    try {
      $result = $this->getClient()->send();
      $code = $this->getClient()->getStatusCode();
      $this->getResponse()->setResponseBody($result);

      if ($code == 200)
      {
        $this->setOk(true);
      }
      else if ($code == 400)
      {
        $this->setOk(false);
        Log::error(sprintf('%s API request returned with code 400!', $this->getResponse()->getPartner()->getIdentifier()), json_decode($result, true));
      }
      else if ($this->getClient()->hasError())
      {
        $this->setOk(false);
        Log::critical(sprintf('%s API request got error!', $this->getResponse()->getPartner()->getIdentifier()), [$this->getClient()->getError()]);
      }
      else {
        $this->setOk(false);
        Log::critical(sprintf('%s API request returned unhandled response (code %s)!', $this->getResponse()->getPartner()->getIdentifier(), $code), [$result] ?? []);
      }
    }
    catch (\Exception $ex)
    {
      Log::critical('API request failed with exception!', [$ex->getMessage()]);
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
    $headers = [
      'Accept: application/json',
      'Content-Type: application/json',
      'Authorization: Basic a3Jpc3RqYW4tdGVzdDprcmlzdGphbi10ZXN0'
    ];

    $options = [];

    $request = $this->getMessage()->getRelatedEntity();
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
    $options[CURLOPT_SSL_VERIFYHOST] = false;

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