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
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

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

  /**
   * @return mixed
   */
  public function getPartnersWithStats()
  {
    $offerClass = 'Broker\Domain\Entity\Offer';
    $qb = $this->entityManager->createQueryBuilder();

    $qb->select('p.name, 
    count(DISTINCT o1.id) rejected, 
    count(DISTINCT o2.id) inProcess,
    count(DISTINCT o3.id) accepted,
    count(DISTINCT o4.id) paidOut,
    count(DISTINCT o5.id) total')
      ->from($this->entityClass, 'p')
      ->leftJoin($offerClass, 'o1', 'WITH', 'p.id = o1.partnerId AND o1.rejectedDate IS NOT NULL AND o1.acceptedDate IS NULL AND o1.paidOutDate IS NULL')
      ->leftJoin($offerClass, 'o2', 'WITH', 'p.id = o2.partnerId AND o2.rejectedDate IS NULL AND o2.acceptedDate IS NULL AND o2.chosenDate IS NULL AND o2.paidOutDate IS NULL')
      ->leftJoin($offerClass, 'o3', 'WITH', 'p.id = o3.partnerId AND o3.acceptedDate IS NOT NULL AND o3.paidOutDate IS NULL AND o3.rejectedDate IS NULL')
      ->leftJoin($offerClass, 'o4', 'WITH', 'p.id = o4.partnerId AND o4.paidOutDate IS NOT NULL')
      ->leftJoin($offerClass, 'o5', 'WITH', 'p.id = o5.partnerId')
      ->groupBy('p.name');

    $query = $qb->getQuery();

/*    $rsm = new ResultSetMapping();
    $rsm->addEntityResult($this->entityClass, 'p');
    $rsm->addFieldResult('p', 'name', 'name');
    $rsm->addFieldResult('p', 'status', 'status');
    // build rsm here

    $query = $this->entityManager->createNativeQuery('SELECT * FROM partner p', $rsm);*/

    $result = $query->getResult();

    return $result;
  }
}