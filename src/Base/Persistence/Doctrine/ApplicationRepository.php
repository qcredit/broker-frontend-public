<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 19.03.18
 * Time: 17:11
 */

namespace App\Base\Persistence\Doctrine;

use Broker\Domain\Interfaces\Repository\ApplicationRepositoryInterface;

class ApplicationRepository extends AbstractRepository implements ApplicationRepositoryInterface
{
  protected $entityClass = 'Broker\Domain\Entity\Application';

  /**
   * @param string $hash
   * @return null|object
   */
  public function getByHash(string $hash)
  {
    return $this->getOneBy(['applicationHash' => $hash]);
  }

  /**
   * @return mixed
   */
  public function getAppsNeedingReminder()
  {
    $date = new \DateTime();
    $query = $this->getQueryBuilder()->select('entity')
      ->from($this->entityClass, 'entity')
      ->where("JSON_EXTRACT(entity.data, '$.email_reminder_sent') < :value")
      ->orWhere("JSON_CONTAINS_PATH(entity.data, 'all', '$.email_reminder_sent') = 0")
      ->andWhere('entity.createdAt < :value')
      ->setParameter('value', $date->modify('-1 day'));

    return $query->getQuery()->execute();
  }

  /**
   * @param string $timeframe
   * @return array
   */
  public function getApplicationStats(string $timeframe = '-1 day')
  {
    if (!in_array($timeframe, ['-1 day', '-1 week', '-1 month']))
    {
      $timeframe = '-1 day';
    }
    $offerClass = 'Broker\Domain\Entity\Offer';
    $qb = $this->entityManager->createQueryBuilder();
    $date = new \DateTime();

    $qb->select('count(a.id) total,
    count(o.id) accepted,
    count(o1.id) rejected,
    count(o2.id) paidOut,
    count(o3.id) inProcess
    ')
      ->from($this->entityClass, 'a')
      ->leftJoin($offerClass, 'o', 'WITH', 'o.applicationId = a.id AND o.acceptedDate IS NOT NULL AND o.paidOutDate IS NULL')
      ->leftJoin($offerClass, 'o1', 'WITH', 'o1.applicationId = a.id AND o1.rejectedDate IS NOT NULL')
      ->leftJoin($offerClass, 'o2', 'WITH', 'o2.applicationId = a.id AND o2.paidOutDate IS NOT NULL')
      ->leftJoin($offerClass, 'o3', 'WITH', 'o3.applicationId = a.id AND o3.rejectedDate IS NULL AND o3.chosenDate IS NULL')
      ->andWhere('a.createdAt > :date')
      ->setParameter('date', $date->modify($timeframe));


    $query = $qb->getQuery();

    $result = $query->getResult();

    return !empty($result) ? $result[0] : [];
  }
}