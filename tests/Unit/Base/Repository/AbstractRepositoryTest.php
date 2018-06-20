<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 22.03.18
 * Time: 17:36
 */

use App\Base\Persistence\Doctrine\AbstractRepository;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManager;

class AbstractRepositoryTest extends TestCase
{
  protected $entityManagerMock;
  protected $containerMock;

  public function setUp()
  {
    $this->entityManagerMock = $this->createMock(EntityManager::class);
    $this->containerMock = $this->createMock(\Slim\Container::class);
  }

  public function test__construct()
  {
    $this->expectException('Exception');

    $this->getMockBuilder(AbstractRepository::class)->setConstructorArgs([$this->entityManagerMock, $this->containerMock])
      ->getMockForAbstractClass();
  }
}
