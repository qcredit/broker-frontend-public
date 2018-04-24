<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 24.04.18
 * Time: 10:24
 */
require_once __DIR__ . '/../vendor/autoload.php';

$jobby = new Jobby\Jobby([
  'debug' => true,
  'output' => __DIR__ . '/../logs/cron.log'
]);

$jobby->add('SendChooseOffer', [
  'closure' => function() {
    require(__DIR__ . '/../vendor/autoload.php');
    $settings = require(__DIR__ . '/settings.php');
    $app = new \Slim\App($settings);
    require(__DIR__ . '/dependencies.php');
    $job = new App\Cron\SendChooseOffer($container->get('MessageDeliveryService'), $container->get('ApplicationRepository'));
    return $job->run();
  },
  'schedule' => '* * * * *',
  'debug' => true,
  'output' => __DIR__ . '/../logs/cron.log'
]);

$jobby->run();