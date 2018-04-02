<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 28.03.18
 * Time: 17:35
 */

namespace App\Base\Repository;

use Broker\Domain\Interfaces\PartnerDataMapperInterface;
use Broker\Domain\Interfaces\PartnerDataMapperRepositoryInterface;

class PartnerDataMapperRepository implements PartnerDataMapperRepositoryInterface
{
  public function getDataMapperByPartnerId(string $identifier): PartnerDataMapperInterface
  {
    switch($identifier)
    {
      case 'AASA';
        $class = 'App\Base\AasaDataMapper';
        break;
      default:
        throw new \Exception(sprintf('No data mapper for "%s"!', $identifier));
        break;
    }

    return new $class;
  }
}