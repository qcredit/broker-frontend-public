<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 16.03.18
 * Time: 11:07
 */
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require 'vendor/autoload.php';

$settings = include 'src/settings.php';
$settings = $settings['settings']['doctrine'];

$config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
  $settings['meta']['entity_path'],
  $settings['meta']['auto_generate_proxies'],
  $settings['meta']['proxy_dir'],
  $settings['meta']['cache'],
  false
);

$em = \Doctrine\ORM\EntityManager::create($settings['connection'], $config);

return ConsoleRunner::createHelperSet($em);