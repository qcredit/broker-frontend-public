<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 22.03.18
 * Time: 17:55
 */

use App\Base\Persistence\Doctrine\ApplicationRepository;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManager;

class ApplicationRepositoryTest extends TestCase
{
  protected $entityManagerMock;

  public function setUp()
  {
    $entityRepositoryMock = $this->getMockBuilder(\Doctrine\ORM\EntityRepository::class)
      ->disableOriginalConstructor()
      ->getMock();
    $entityRepositoryMock->method('findBy')
      ->willReturn([]);
    $entityRepositoryMock->method('find')
      ->willReturn([]);
    $entityRepositoryMock->method('findAll')
      ->willReturn([]);
    $this->entityManagerMock = $this->createMock(EntityManager::class);
    $this->entityManagerMock->method('getRepository')
      ->willReturn($entityRepositoryMock);
  }

  public function testGetBy()
  {
    $instance = new ApplicationRepository($this->entityManagerMock);

    $this->assertTrue(is_array($instance->getBy(['key' => 'something'])));
  }

  public function testGetById()
  {
    $instance = new ApplicationRepository($this->entityManagerMock);

    $this->assertTrue(is_array($instance->getById(10)));
  }

  public function testGetAll()
  {
    $instance = new ApplicationRepository($this->entityManagerMock);

    $this->assertTrue(is_array($instance->getAll()));
  }
}