<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 19.04.18
 * Time: 15:39
 */

namespace App\Base\Interfaces;

use Broker\Domain\Entity\PartnerRequest;
use Broker\Domain\Entity\PartnerResponse;

interface PartnerDeliveryInterface
{
  public function send(PartnerRequest $request): PartnerResponse;
  public function isOk(): bool;
}