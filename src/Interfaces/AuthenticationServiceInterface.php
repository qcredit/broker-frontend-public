<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 12.04.18
 * Time: 09:47
 */

namespace App\Interfaces;

interface AuthenticationServiceInterface
{
  public function setPayload(array $payload);
  public function authenticate(): bool;
  public function getName(): string;
}