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
    if (is_array($settings['processor']))
    {
      foreach ($settings['processor'] as $processor)
      {
        $logger->pushProcessor($processor);
      }
    }
    else {
      $logger->pushProcessor($settings['processor']);
    }
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$brokerSettings = $container->get('settings')['broker'];
$brokerSettings['logger'] = array_merge($container->get('settings')['logger'], $brokerSettings['logger']);

Config::getInstance()->setConfig($brokerSettings);

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

$container['RepositoryFactory'] = function($c)
{
  return new \Broker\persistence\doctrine\RepositoryFactory();
};

$container['UserRepository'] = function($container) {
  return $container->get('RepositoryFactory')->createGateway($container->get('db'), 'User');
/*  return new UserRepository(
    $container->get('db')
  );*/
};

$container['PartnerController'] = function($c) {
  $partnerRepository = $c->get('RepositoryFactory')->createGateway($c->get('db'), 'Partner');

  $partnerDataLoader = new \App\Base\Repository\PartnerExtraDataLoader(new PartnerDataMapperRepository());
  return new \App\Controllers\Admin\PartnerController($partnerRepository, new \Broker\Domain\Factory\PartnerFactory(), $partnerDataLoader, $c);
};

$container['AdminApplicationController'] = function($c)
{
  $appRepository = $c->get('RepositoryFactory')->createGateway($c->get('db'), 'Application');
  $offerRepository = $c->get('RepositoryFactory')->createGateway($c->get('db'), 'Offer');
  return new \App\Controllers\Admin\AdminApplicationController($appRepository, $offerRepository, $c);
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