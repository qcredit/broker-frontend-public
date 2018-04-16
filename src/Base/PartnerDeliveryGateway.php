<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 03.04.18
 * Time: 13:26
 */

namespace App\Base;

use Broker\Domain\Entity\Partner;
use Broker\Domain\Entity\PartnerResponse;
use Broker\Domain\Entity\PartnerRequest;
use Broker\Infrastructure\AbstractPartnerDeliveryGateway;
use Broker\System\Log;

class PartnerDeliveryGateway extends AbstractPartnerDeliveryGateway
{
  /**
   * @var bool
   */
  protected $ok = false;

  /**
   * @param PartnerRequest $request
   * @return PartnerResponse
   * @todo Set up offers via e-mail!
   */
  public function send(PartnerRequest $request)
  {
    $partner = $request->getPartner();

    if ($partner->getUseApi())
    {
      return $this->sendApiRequest($request);
    }

    return (new PartnerResponse)->setPartner($partner);
  }

  /**
   * @return bool
   */
  public function isOk(): bool
  {
    return $this->ok;
  }

  protected function sendApiRequest(PartnerRequest $request)
  {
    $ch = curl_init();

    $header = [
      'Accept: application/json',
      'Content-Type: application/json',
      'Authorization: Basic a3Jpc3RqYW4tdGVzdDprcmlzdGphbi10ZXN0'
    ];

    if ($request->getType() === PartnerRequest::REQUEST_TYPE_INITIAL)
    {
      curl_setopt($ch, CURLOPT_URL, $request->getPartner()->getApiTestUrl());
      curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getRequestPayload());
    }
    elseif ($request->getType() === PartnerRequest::REQUEST_TYPE_UPDATE)
    {
      $header[] = sprintf('X-Auth-Token: %s', $request->getOffer()->getDataElement('token'));
      curl_setopt($ch, CURLOPT_URL, $request->getPartner()->getApiTestUrl() . "/" . $request->getOffer()->getRemoteId());
    }
    elseif ($request->getType() === PartnerRequest::REQUEST_TYPE_CHOOSE)
    {
      $header[] = sprintf('X-Auth-Token: %s', $request->getOffer()->getDataElement('token'));
      curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getRequestPayload());
      curl_setopt($ch, CURLOPT_URL, $request->getPartner()->getApiTestUrl() . "/" . $request->getOffer()->getRemoteId());
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    $result = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $response = curl_getinfo($ch);
    curl_close($ch);

    $partnerResponse = new PartnerResponse();
    $partnerResponse->setPartner($request->getPartner())
      ->setOffer($request->getOffer())
      ->setType($request->getType())
      ->setResponseBody($result);

    if ($code == 200)
    {
      $partnerResponse->setOk(true);
    }
    else if ($code == 400)
    {
      $partnerResponse->setOk(false);
      Log::error(sprintf('%s API request returned with code 400!', $request->getPartner()->getIdentifier()), json_decode($result, true), $response);
    }
    else {
      $partnerResponse->setOk(false);
      Log::critical(sprintf('%s API request returned unhandled response (code %s)!', $request->getPartner()->getIdentifier(), $code), json_decode($result,true) ?? [], $response);
    }

    return $partnerResponse;
  }

  protected function sendEmailRequest()
  {

  }
}