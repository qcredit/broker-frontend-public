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

    $authorization = 'Authorization: Basic a3Jpc3RqYW4tdGVzdDprcmlzdGphbi10ZXN0';

    $header = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json',
      $authorization
    ];

    curl_setopt($ch, CURLOPT_URL, $request->getPartner()->getApiTestUrl());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getRequestPayload());

    $result = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $response = curl_getinfo($ch);
    curl_close($ch);

    $partnerResponse = new PartnerResponse();
    $partnerResponse->setPartner($request->getPartner());
    $partnerResponse->setResponseBody($result);

    if ($code == 200)
    {
      $partnerResponse->setOk(true);
    }
    else if ($code == 400)
    {
      $partnerResponse->setOk(false);
    }
    else {
      $partnerResponse->setOk(false);
      Log::critical(sprintf('%s API request returned unhandled response!', $request->getPartner()->getIdentifier()), $response);
    }

    return $partnerResponse;
  }

  protected function sendEmailRequest()
  {

  }
}