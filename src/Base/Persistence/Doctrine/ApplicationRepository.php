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
}