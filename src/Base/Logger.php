<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 23.03.18
 * Time: 11:16
 */

namespace App\Base;

use Broker\Domain\Interfaces\LoggerInterface;
use Monolog\Logger as Monolog;
use Monolog\Handler\StreamHandler;

class Logger implements LoggerInterface
{
  protected $logger;

  /**
   * Logger constructor.
   * @param $config
   * @throws \Exception
   */
  public function __construct()
  {
    $this->logger = new Monolog('broker');
  }

  /**
   * @param $config
   * @throws \Exception
   */
  public function setConfig($config)
  {
    $this->logger->pushHandler(new StreamHandler($config['logFile'], $config['logLevel']));
  }

  /**
   * @param $message
   * @param array $context
   */
  public function debug($message, array $context = [])
  {
    $this->logger->debug($message, $context);
  }

  /**
   * @param $message
   * @param array $context
   */
  public function info($message, array $context = [])
  {
    $this->logger->info($message, $context);
  }

  /**
   * @param $message
   * @param array $context
   */
  public function notice($message, array $context = [])
  {
    $this->logger->notice($message, $context);
  }

  /**
   * @param $message
   * @param array $context
   */
  public function warning($message, array $context = [])
  {
    $this->logger->warning($message, $context);
  }

  /**
   * @param $message
   * @param array $context
   */
  public function error($message, array $context = [])
  {
    $this->logger->error($message, $context);
  }

  /**
   * @param $message
   * @param array $context
   */
  public function critical($message, array $context = [])
  {
    $this->logger->critical($message, $context);
  }

  /**
   * @param $message
   * @param array $context
   */
  public function alert($message, array $context = [])
  {
    $this->logger->alert($message, $context);
  }

  /**
   * @param $message
   * @param array $context
   */
  public function emergency($message, array $context = [])
  {
    $this->logger->emergency($message, $context);
  }

  /**
   * @param $level
   * @param $message
   * @param array $context
   */
  public function log($level, $message, array $context = [])
  {
    $this->logger->log($level, $message, $context);
  }
}