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
}