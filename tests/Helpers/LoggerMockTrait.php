<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 23.05.18
 * Time: 10:34
 */

namespace Tests\Helpers;

use Monolog\Logger;

trait LoggerMockTrait
{
  protected $loggerMock;

  public function __construct()
  {
    $this->loggerMock = $this->createMock(Logger::class);
    parent::__construct();
  }
}