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
   * @return mixed
   */
  public function getClient()
  {
    return $this->client;
  }

  /**
   * @param mixed $client
   * @return ApiDelivery
   */
  public function setClient($client)
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
    $this->setClient(curl_init());
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
    $this->makeCall();
  }

  /**
   * @throws \Exception
   */
  protected function makeCall()
  {
    try {
      $result = $this->exec();
      $code = $this->getResponseCode();
      curl_close($this->getClient());

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

  /**
   * @return mixed
   */
  protected function exec()
  {
    return curl_exec($this->getClient());
  }

  /**
   * @return mixed
   */
  protected function getResponseCode()
  {
    return curl_getinfo($this->getClient(), CURLINFO_HTTP_CODE);
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

    $request = $this->getMessage()->getRelatedEntity();
    if ($request->getType() === PartnerRequest::REQUEST_TYPE_INITIAL)
    {
      $this->setClientOption(CURLOPT_URL, $request->getPartner()->getApiTestUrl());
      $this->setClientOption(CURLOPT_POSTFIELDS, $request->getRequestPayload());
    }
    else if ($request->getType() === PartnerRequest::REQUEST_TYPE_UPDATE)
    {
      $this->setClientOption(CURLOPT_URL, $request->getPartner()->getApiTestUrl() . "/" . $request->getOffer()->getRemoteId());
      $headers[] = sprintf('X-Auth-Token: %s', $request->getOffer()->getDataElement('token'));
    }
    else if ($request->getType() === PartnerRequest::REQUEST_TYPE_CHOOSE)
    {
      $headers[] = sprintf('X-Auth-Token: %s', $request->getOffer()->getDataElement('token'));
      $this->setClientOption(CURLOPT_URL, $request->getPartner()->getApiTestUrl() . "/" . $request->getOffer()->getRemoteId());
      $this->setClientOption(CURLOPT_POSTFIELDS, $request->getRequestPayload());
      $this->setClientOption(CURLOPT_CUSTOMREQUEST, 'PATCH');

      //@todo Move this line away from herrre!!!!11!1!11!!1
      $this->getResponse()->setOffer($request->getOffer());
    }

    $this->setClientOption(CURLOPT_RETURNTRANSFER, true);
    $this->setClientHeaders($headers);
  }

  /**
   * @param $option
   * @param $value
   */
  protected function setClientOption($option, $value)
  {
    curl_setopt($this->getClient(), $option, $value);
  }

  /**
   * @param $headers
   */
  protected function setClientHeaders($headers)
  {
    curl_setopt($this->getClient(), CURLOPT_HTTPHEADER, $headers);
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