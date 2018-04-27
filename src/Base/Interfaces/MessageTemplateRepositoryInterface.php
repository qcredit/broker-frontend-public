<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 26.04.18
 * Time: 15:16
 */

namespace App\Base\Interfaces;

use Broker\Domain\Entity\Message;

interface MessageTemplateRepositoryInterface
{
  public function getTemplateByPath(string $path, array $arguments = []): string;
}