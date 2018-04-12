<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 04.04.18
 * Time: 16:18
 */

namespace App\Base\Persistence\Doctrine;

use Broker\Domain\Entity\Application;
use Broker\Domain\Interfaces\Repository\OfferRepositoryInterface;

class OfferRepository extends AbstractRepository implements OfferRepositoryInterface
{
  protected $entityClass = 'Broker\Domain\Entity\Offer';

  /**
   * @param Application $application
   * @return array
   */
  public function getOffersByApplication(Application $application): array
  {
    return $this->getBy(['applicationId' => $application->getId()]) ?? [];
  }
}