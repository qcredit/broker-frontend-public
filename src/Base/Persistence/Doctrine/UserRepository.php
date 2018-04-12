<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 11.04.18
 * Time: 09:43
 */

namespace App\Base\Persistence\Doctrine;

use App\Base\Interfaces\UserRepositoryInterface;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
  protected $entityClass = 'App\Model\User';

  /**
   * @param string $email
   * @return null|object
   */
  public function getByEmail(string $email)
  {
    return $this->getOneBy(['email' => $email]);
  }
}