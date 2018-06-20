<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 16.03.18
 * Time: 13:56
 */

namespace App\Base\Factory;

use Slim\Container;

class RepositoryFactory
{
  public function createGateway($entityManager, $entityName, Container $container)
  {
    $class = 'App\\Base\\Persistence\\Doctrine\\' . $entityName . 'Repository';

    if (class_exists($class))
    {
      return new $class($entityManager, $container);
    }

    throw new \RuntimeException(sprintf('Unknown Repository requested: %s', $class));
  }
}