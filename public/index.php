<?php
define('PUBLIC_ROOT', __DIR__);
define('CODE_ROOT', dirname(__DIR__));

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

require __DIR__ . '/../vendor/autoload.php';

$fatality = new \App\Component\Fatality();

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
