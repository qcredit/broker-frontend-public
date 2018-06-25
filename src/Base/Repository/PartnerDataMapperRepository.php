<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 28.03.18
 * Time: 17:35
 */

namespace App\Base\Repository;

use Broker\Domain\Interfaces\PartnerDataMapperInterface;
use Broker\Domain\Interfaces\Repository\PartnerDataMapperRepositoryInterface;
use Broker\System\Error\InvalidConfigException;

class PartnerDataMapperRepository implements PartnerDataMapperRepositoryInterface
{
  /**
   * @param string $identifier
   * @return PartnerDataMapperInterface
   * @throws \Broker\System\Error\InvalidConfigException
   * @throws \Exception
   */
  public function getDataMapperByPartnerId(string $identifier): PartnerDataMapperInterface
  {
    switch($identifier)
    {
      case 'AASA';
        $class = 'App\Base\Partner\Aasa\DataMapper';
        break;
      case 'TEST':
        $class = 'App\Base\Partner\Aasa\DataMapper';
        break;
      default:
        throw new InvalidConfigException(sprintf('No data mapper for "%s"!', $identifier));
        break;
    }

    return new $class;
  }
}