<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 15.03.18
 * Time: 16:24
 */

namespace App\Base\Persistence\Doctrine;

use Broker\Domain\Interfaces\Repository\RepositoryInterface;
use Doctrine\ORM\EntityManager;

abstract class AbstractRepository implements RepositoryInterface
{
  protected $entityManager;
  protected $entityClass;

  /**
   * AbstractRepository constructor.
   * @param EntityManager $entityManager
   * @throws \Exception
   */
  public function __construct(EntityManager $entityManager)
  {
    if (empty($this->entityClass))
    {
      throw new \Exception(get_class($this) . '::$entityClass is not defined');
    }

    $this->entityManager = $entityManager;
  }

  /**
   * @param $criteria
   * @param null $orderBy
   * @param null $limit
   * @param null $offset
   * @return array
   */
  public function getBy($criteria, $orderBy = null, $limit = null, $offset = null): array
  {
    return $this->entityManager->getRepository($this->entityClass)->findBy($criteria, $orderBy, $limit, $offset) ?? [];
  }

  /**
   * @param $criteria
   * @param null $orderBy
   * @param null $limit
   * @param null $offset
   * @return null|object
   */
  public function getOneBy($criteria, $orderBy = null, $limit = null, $offset = null)
  {
    return $this->entityManager->getRepository($this->entityClass)->findOneBy($criteria, $orderBy, $limit, $offset);
  }

  /**
   * @param $id
   * @return array
   */
  public function getById($id): array
  {
    $result = $this->entityManager->getRepository($this->entityClass)
      ->find($this->entityClass, intval($id));

    return $result;
  }

  /**
   * @return array
   */
  public function getAll(): array
  {
    $results = $this->entityManager->getRepository($this->entityClass)->findAll();

    return $results;
  }

  /**
   * @param $entity
   * @return $this
   * @throws \Doctrine\ORM\ORMException
   * @throws \Doctrine\ORM\OptimisticLockException
   */
  public function save($entity)
  {
    $this->entityManager->persist($entity);
    $this->entityManager->flush();

    return $this;
  }
}