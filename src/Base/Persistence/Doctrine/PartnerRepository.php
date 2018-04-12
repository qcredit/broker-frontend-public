<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 23.03.18
 * Time: 15:02
 */

namespace App\Base\Persistence\Doctrine;

use Broker\Domain\Entity\Partner;
use Broker\Domain\Interfaces\Repository\PartnerRepositoryInterface;

class PartnerRepository extends AbstractRepository implements PartnerRepositoryInterface
{
  protected $entityClass = 'Broker\Domain\Entity\Partner';

  /**
   * @return array
   */
  public function getActivePartners(): array
  {
    return $this->getBy(['status' => Partner::STATUS_ENABLED]) ?? [];
  }
}