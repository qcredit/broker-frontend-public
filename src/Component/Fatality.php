<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 26.06.18
 * Time: 13:38
 */

namespace App\Component;

class Fatality
{
  public function __construct()
  {
    register_shutdown_function( [$this, "fatal_handler"]);
  }

  public function fatal_handler()
  {
    // Determine if there was an error and that is why we are about to exit.
    $error = error_get_last();

    if ($error !== null && is_array($error) && $error['type'] == E_ERROR)
    {
      $settings = require CODE_ROOT . '/src/settings.php';
      $app = new \Slim\App($settings);

      require CODE_ROOT . '/src/dependencies.php';

      $view = $app->getContainer()->get('view');

      $logger = $app->getContainer()->get('logger');
      $logger->emergency('An unrecoverable error has ocurred!', $error);

      echo $view->fetch('error.twig');
    }

    exit;
  }
}