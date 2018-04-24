<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 19.04.18
 * Time: 15:40
 */

namespace App\Base\Interfaces;

use Broker\Domain\Entity\Partner;

interface PartnerDeliveryStrategyFactoryInterface
{
  public function create(Partner $partner);
}