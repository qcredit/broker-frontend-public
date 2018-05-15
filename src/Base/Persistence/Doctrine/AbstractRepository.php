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
  protected $queryBuilder;

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
   * @return EntityManager
   */
  public function getEntityManager()
  {
    return $this->entityManager;
  }

  /**
   * @param EntityManager $entityManager
   * @return AbstractRepository
   */
  public function setEntityManager(EntityManager $entityManager)
  {
    $this->entityManager = $entityManager;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getEntityClass()
  {
    return $this->entityClass;
  }

  /**
   * @param mixed $entityClass
   * @return AbstractRepository
   */
  public function setEntityClass($entityClass)
  {
    $this->entityClass = $entityClass;
    return $this;
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

  /**
   * @param $entity
   * @return bool
   * @throws \Doctrine\ORM\ORMException
   * @throws \Doctrine\ORM\OptimisticLockException
   */
  public function delete($entity)
  {
    $this->entityManager->remove($entity);
    $this->entityManager->flush();

    return true;
  }

  /**
   * @param string $field
   * @param string $value
   * @param string $path
   * @param bool $not
   * @return mixed
   * @see https://dev.mysql.com/doc/refman/5.7/en/json-search-functions.html#function_json-contains
   */
  public function getWhereJsonContainsValue(string $field, string $value, string $path, bool $not = false)
  {
    $queryBuilder = $this->entityManager->createQueryBuilder();
    $query = $queryBuilder->select('entity')
      ->from($this->entityClass, 'entity')
      ->where("JSON_CONTAINS(entity.$field, :value, :path) = :not");

    $query->setParameter('path', '$.'.$path);
    $query->setParameter('value', json_encode($value));
    $query->setParameter('not', $not ? 0 : 1);

    $q = $query->getQuery();

    return $q->execute();
  }

  /**
   * @param string $field
   * @param string $oneOrAll
   * @param string $path
   * @return mixed
   * @todo Make improvements by providing a way to use nested or multiple paths!
   * @see https://dev.mysql.com/doc/refman/5.7/en/json-search-functions.html#function_json-contains-path
   */
  public function getWhereJsonContainsPath(string $field, string $oneOrAll, string $path)
  {
    $queryBuilder = $this->getQueryBuilder();
    $query = $queryBuilder->select('entity')
      ->from($this->entityClass, 'entity')
      ->where("JSON_CONTAINS_PATH(entity.$field, 'one', :path) = 1");

    $query->setParameter('path', '$.' . $path);

    $q = $query->getQuery();
    return $q->execute();
  }

  /**
   * @param string $field
   * @param array $condition
   * @return mixed
   */
  public function getByJsonExtract(string $field, array $condition)
  {
    $query = $this->getQueryBuilder()->select('entity')
      ->from($this->entityClass, 'entity')
      ->where("JSON_EXTRACT(entity.$field, :path) $condition[1] :value")
      ->setParameter('path', '$.' . $condition[0])
      ->setParameter('value', $condition[2]);

    return $query->getQuery()->execute();
  }

  /**
   * @return \Doctrine\ORM\QueryBuilder
   */
  public function getQueryBuilder()
  {
    return $this->getEntityManager()->createQueryBuilder();
  }
}