<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 11.04.18
 * Time: 10:02
 */

namespace App\Base\Factory;

use App\Base\Interfaces\UserFactoryInterface;
use App\Model\User;

/**
 * Class UserFactory
 * @package App\Base\Factory
 */
class UserFactory implements UserFactoryInterface
{
  /**
   * @return User
   */
  public function create(): User
  {
    $user = new User();
    return $user->setCreatedAt(new \DateTime());
  }
}