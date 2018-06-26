<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 24.04.18
 * Time: 10:56
 */

namespace App\Cron;

use Monolog\Logger;

interface BaseJob
{
  const CRON_LOG_PATH = '/var/log/apache2/broker-cron.log';
  const CRON_LOG_FACILITY = 'CRON';
  const CRON_LOG_LEVEL = Logger::DEBUG;

  public function run(): bool;
}