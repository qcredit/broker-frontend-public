<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

if (getenv('ENV_TYPE') == 'production')
{
  ini_set('display_errors', 0);
  ini_set('display_startup_errors', 0);
}

global $fatality;

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
      $settings = require __DIR__ . '/../src/settings.php';
      $app = new \Slim\App($settings);

      require __DIR__ . '/../src/dependencies.php';

      $view = $app->getContainer()->get('view');

      $logger = $app->getContainer()->get('logger');
      $logger->emergency('An unrecoverable error has ocurred!', $error);

      echo $view->fetch('error.twig');
    }

    exit;
  }
}

$fatality = new Fatality();

require __DIR__ . '/../vendor/autoload.php';

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';

$app = new \Slim\App($settings);

session_start();

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';

$app->run();
