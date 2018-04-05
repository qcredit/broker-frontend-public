<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 03.04.18
 * Time: 13:26
 */

namespace App\Base;

use Broker\Domain\Entity\PartnerResponse;
use Broker\Domain\Entity\PartnerRequest;
use Broker\Infrastructure\AbstractPartnerDeliveryGateway;
use Broker\System\Log;

class PartnerDeliveryGateway extends AbstractPartnerDeliveryGateway
{
  protected $apiUrl = 'http://crm-test.asakredyt.pl/api/v1/loans';
  /**
   * @var bool
   */
  protected $ok = false;

  public function send(PartnerRequest $request)
  {
    $partner = $request->getPartner();

/*    if ($partner->getHasApi())
    {
      $this->sendApiRequest($request);
    }*/
    return $this->sendApiRequest($request);
  }

  /**
   * @return bool
   */
  public function isOk(): bool
  {
    return $this->ok;
  }

  protected function sendApiRequest($request)
  {
    $ch = curl_init();

    $authorization = 'Authorization: Basic a3Jpc3RqYW4tdGVzdDprcmlzdGphbi10ZXN0';

    $header = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json',
      $authorization
    ];

    curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
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
}