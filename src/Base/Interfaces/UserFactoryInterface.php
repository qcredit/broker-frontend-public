<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 11.04.18
 * Time: 10:03
 */

namespace App\Base\Interfaces;

use App\Model\User;
use Broker\Domain\Interfaces\Factory\FactoryInterface;

interface UserFactoryInterface extends FactoryInterface
{
  public function create(): User;
}