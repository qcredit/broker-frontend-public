<?php
use Broker\System\Config;
use App\Base\Repository\PartnerDataMapperRepository;
use Broker\Domain\Service\NewApplicationService;
use Broker\Domain\Factory\ApplicationFactory;
use Broker\Domain\Service\PartnerRequestsService;
use Broker\Domain\Service\PartnerResponseService;
use App\Base\PartnerDeliveryGateway;
use Broker\Domain\Factory\OfferFactory;
use Broker\Domain\Service\PreparePartnerRequestsService;
use Broker\Domain\Factory\PartnerRequestFactory;

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

Config::getInstance()->setConfig($container->get('settings')['broker']);

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
/*  return new UserRepository(
    $container->get('db')
  );*/
};

$container['PartnerController'] = function($c) {
  $factory = new \Broker\Persistence\Doctrine\RepositoryFactory();
  $partnerRepository = $factory->createGateway($c->get('db'), 'Partner');
  return new \App\Controllers\Admin\PartnerController($partnerRepository, new \Broker\Domain\Factory\PartnerFactory(), $c);
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
  $factory = new \Broker\Persistence\Doctrine\RepositoryFactory();
  $partnerRepository = $factory->createGateway($c->get('db'), 'Partner');
  $appRepository = $factory->createGateway($c->get('db'), 'Application');
  $offerRepository = $factory->createGateway($c->get('db'), 'Offer');
  $partnerDataMapperRepository = new PartnerDataMapperRepository();

  $newApplicationService = new NewApplicationService(
    new ApplicationFactory(),
    $appRepository,
    $partnerRepository,
    $partnerDataMapperRepository
  );

  $requestService = new PartnerRequestsService(
    new PartnerDeliveryGateway(),
    $partnerDataMapperRepository
  );
  $responseService = new PartnerResponseService(
    new OfferFactory(),
    $offerRepository,
    $partnerDataMapperRepository
  );
  $prepareService = new PreparePartnerRequestsService(
    $partnerRepository,
    $newApplicationService,
    $requestService,
    $responseService,
    new PartnerRequestFactory()
  );

  $offerService = new \Broker\Domain\Service\ApplicationOfferListService($appRepository, $offerRepository);

  return new \App\Controllers\ApplicationController(
    $prepareService,
    $offerService,
    $c
  );
};