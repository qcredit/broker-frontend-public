<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 24.04.18
 * Time: 10:24
 */
require_once __DIR__ . '/../vendor/autoload.php';

$jobby = new Jobby\Jobby([
  'debug' => false,
  'output' => __DIR__ . '/../logs/cron.log'
]);

$jobby->add('SendChooseOfferReminder', [
  'closure' => function() {
    require(__DIR__ . '/../vendor/autoload.php');
    $settings = require(__DIR__ . '/settings.php');
    $app = new \Slim\App($settings);
    require(__DIR__ . '/dependencies.php');
    $job = new App\Cron\SendChooseOfferReminder($container->get('MessageDeliveryService'),
      $container->get('ApplicationRepository'),
      new \Broker\Domain\Factory\MessageFactory(),
      $container->get('MessageTemplateRepository')
    );
    return $job->run();
  },
  'schedule' => '* * * * *',
]);

$jobby->run();