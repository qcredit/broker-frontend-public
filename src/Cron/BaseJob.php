<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 24.04.18
 * Time: 10:56
 */

namespace App\Cron;

interface BaseJob
{
  public function run(): bool;
}