<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 15.03.18
 * Time: 16:24
 */

namespace App\Base\Persistence\Doctrine;

use App\Base\Logger;
use Broker\Domain\Interfaces\Repository\RepositoryInterface;
use Doctrine\ORM\EntityManager;
use Slim\Container;

abstract class AbstractRepository implements RepositoryInterface
{
  protected $entityManager;
  protected $entityClass;
  protected $queryBuilder;
  /**
   * @var Container
   */
  protected $container;

  /**
   * AbstractRepository constructor.
   * @param EntityManager $entityManager
   * @param Container $container
   * @throws \Exception
   */
  public function __construct(EntityManager $entityManager, Container $container)
  {
    if (empty($this->entityClass))
    {
      throw new \Exception(get_class($this) . '::$entityClass is not defined');
    }

    $this->entityManager = $entityManager;
    $this->container = $container;
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
   * @return Container
   */
  public function getContainer()
  {
    return $this->container;
  }

  /**
   * @return Logger
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getLogger()
  {
    return $this->getContainer()->get('logger');
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
   * @return object
   */
  public function getById($id): object
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
   * @param array $conditions
   * @return int
   */
  public function getCount(array $conditions = []): int
  {
    return $this->entityManager->getRepository($this->entityClass)->count($conditions);
  }

  /**
   * @param $entity
   * @return bool
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function save($entity): bool
  {
    try {
      $this->entityManager->persist($entity);
      $this->entityManager->flush();

      return true;
    }
    catch (\Exception $ex)
    {
      $this->getLogger()->error($ex->getMessage());
      return false;
    }
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