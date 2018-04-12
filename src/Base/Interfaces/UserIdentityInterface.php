<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 12.04.18
 * Time: 09:53
 */

namespace App\Base\Interfaces;

interface UserIdentityInterface
{
  public function getRole();
  public function getAuthKey();
  public function generateAuthKey();
  public function isGuest(): bool;
  public function validateAuthKey(string $authKey): bool;
}