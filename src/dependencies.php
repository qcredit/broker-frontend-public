<?php
use Broker\Persistence\Doctrine\UserRepository;
use App\Models\User;
$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['view'] = function($container) {
  $view = new \Slim\Views\Twig(dirname(__DIR__) . '/templates');

  // Instantiate and add Slim specific extension
  $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
  $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

  return $view;
};

$container['db'] = function($container) {
  $settings = $container->get('settings')['doctrine'];

  $config = \Doctrine\ORM\Tools\Setup::createYAMLMetadataConfiguration([dirname(__DIR__) . '/vendor/aasa/broker/src/Persistence/Doctrine/Mappings'], true);

  $entityManager = \Doctrine\ORM\EntityManager::create($settings['connection'], $config);

  return $entityManager;
};

$container['UserRepository'] = function($container) {
  $factory = new \Broker\persistence\doctrine\RepositoryFactory();
  return $factory->createGateway($container->get('db'), 'User');
};

$container['UserController'] = function($c) {
  $view = $c->get('view');
  return new \App\Controllers\UserController($view);
};

$container['HomeController'] = function($c)
{
  $view = $c->get('view');
  return new \App\Controllers\HomeController($view);
};

$container['AdminController'] = function($c)
{
  $view = $c->get('view');
  return new \App\Controllers\AdminController($view);
};

$container['ApplicationController'] = function ($c)
{
  return new \App\Controllers\ApplicationController(
    $c->get('view')
  );
};