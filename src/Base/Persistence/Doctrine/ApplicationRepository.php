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
   * @param string $field
   * @param string $value
   * @param string $path
   * @return mixed
   */
  public function getByJsonContains(string $field, string $value, string $path)
  {
    $queryBuilder = $this->entityManager->createQueryBuilder();
    $query = $queryBuilder->select('a')
      ->from($this->entityClass, 'a')
      ->where("JSON_CONTAINS(a.$field, :value, :path) = 1");

    $query->setParameter('path', '$.'.$path);
    $query->setParameter('value', json_encode($value));

    $q = $query->getQuery();

    return $q->execute();
  }

  public function getByJsonContainsPath()
  {

  }
}