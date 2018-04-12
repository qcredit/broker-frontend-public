<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 11.04.18
 * Time: 09:43
 */

namespace App\Base\Interfaces;

use Broker\Domain\Interfaces\Repository\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
  public function getByEmail(string $email);
}