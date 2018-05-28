<?php
require_once __DIR__ . '/../vendor/autoload.php';

use malkusch\lock\mutex\PHPRedisMutex;

$redis = new Redis();
$redis->connect('redis', 6379);

$jobby = new Jobby\Jobby([
  'debug' => false,
  'output' => '/var/log/apache2/broker-cron.log'
]);

$mutex = new PHPRedisMutex([$redis], 'SendChooseOfferReminder');

$mutex->check(function() use ($jobby) {
  return true;
})->then(function() use ($jobby) {

  $jobby->add('SendChooseOfferReminder', [
    'closure' => function() {
      require(__DIR__ . '/../vendor/autoload.php');
      $settings = require(__DIR__ . '/settings.php');
      $app = new \Slim\App($settings);
      require(__DIR__ . '/dependencies.php');
      $job = new App\Cron\SendChooseOfferReminder(
        $container,
        $container->get('MessageDeliveryService'),
        $container->get('ApplicationRepository'),
        $container->get('MessageTemplateRepository')
      );
      return $job->run();
    },
    'schedule' => '*/2 * * * *',
  ]);

  sleep(2);

});

$jobby->run();