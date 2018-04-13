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

$container['view'] = function($container) {
  $view = new \Slim\Views\Twig(dirname(__DIR__) . '/templates');

  // Instantiate and add Slim specific extension
  $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
  $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

  return $view;
};

$container['session'] = function() {
  return new \SlimSession\Helper;
};

$container['flash'] = function() {
  return new \Slim\Flash\Messages();
};

$container['db'] = function($container) {
  $settings = $container->get('settings')['doctrine'];

  $config = \Doctrine\ORM\Tools\Setup::createYAMLMetadataConfiguration([dirname(__DIR__) . '/src/Base/Persistence/Doctrine/Mapping'], true);

  $entityManager = \Doctrine\ORM\EntityManager::create($settings['connection'], $config);

  return $entityManager;
};

$container['RepositoryFactory'] = function($c)
{
  return new \App\Base\Factory\RepositoryFactory();
};

$container['UserRepository'] = function($container) {
  return $container->get('RepositoryFactory')->createGateway($container->get('db'), 'User');
/*  return new UserRepository(
    $container->get('db')
  );*/
};

$container['AdminController'] = function($c)
{
  return new \App\Controller\Admin\AdminController($c);
};

$container['PartnerController'] = function($c) {
  $partnerRepository = $c->get('RepositoryFactory')->createGateway($c->get('db'), 'Partner');

  $partnerDataLoader = new \App\Base\Repository\PartnerExtraDataLoader(new PartnerDataMapperRepository());
  return new \App\Controller\Admin\PartnerController($partnerRepository, new \Broker\Domain\Factory\PartnerFactory(), $partnerDataLoader, $c);
};

$container['AdminApplicationController'] = function($c)
{
  $appRepository = $c->get('RepositoryFactory')->createGateway($c->get('db'), 'Application');
  $offerRepository = $c->get('RepositoryFactory')->createGateway($c->get('db'), 'Offer');
  return new \App\Controller\Admin\AdminApplicationController($appRepository, $offerRepository, $c);
};

$container['UserController'] = function($c) {
  $userFactory = new \App\Base\Factory\UserFactory();
  $userRepository = $c->get('RepositoryFactory')->createGateway($c->get('db'), 'User');
  $validator = new \App\Base\Validator\UserValidator();
  return new \App\Controller\Admin\UserController($userRepository, $userFactory, $validator, $c);
};

$container['HomeController'] = function($c)
{
  $view = $c->get('view');
  return new \App\Controller\HomeController($view);
};

$container['PartnerDataMapperRepository'] = function($c)
{
  return new PartnerDataMapperRepository();
};

$container['PartnerRequestsService'] = function($c)
{
  return new PartnerRequestsService(
    new PartnerDeliveryGateway(),
    $c->get('PartnerDataMapperRepository')
  );
};

$container['PartnerResponseService'] = function($c)
{
  return new PartnerResponseService(
    new OfferFactory(),
    $c->get('RepositoryFactory')->createGateway($c->get('db'), 'Offer'),
    $c->get('PartnerDataMapperRepository')
  );
};

$container['ChooseOfferService'] = function($c)
{
  return new \Broker\Domain\Service\ChooseOfferService(
    $c->get('PartnerRequestsService'),
    $c->get('PartnerResponseService'),
    new PartnerRequestFactory(),
    new PartnerDataMapperRepository(),
    new \App\Base\Validator\SchemaValidator()
  );
};

$container['ApplicationController'] = function ($c)
{
  $factory = $c->get('RepositoryFactory');
  $appRepository = $factory->createGateway($c->get('db'), 'Application');
  $offerRepository = $factory->createGateway($c->get('db'), 'Offer');
  $schemaValidator = new \App\Base\Validator\SchemaValidator();

  $newApplicationService = new NewApplicationService(
    new ApplicationFactory(),
    $appRepository,
    $factory->createGateway($c->get('db'), 'Partner'),
    $c->get('PartnerDataMapperRepository'),
    $schemaValidator
  );

  $prepareService = new PreparePartnerRequestsService(
    $newApplicationService,
    $c->get('PartnerRequestsService'),
    $c->get('PartnerResponseService'),
    new PartnerRequestFactory()
  );

  return new \App\Controller\ApplicationController(
    $prepareService,
    $appRepository,
    $offerRepository,
    $c->get('ChooseOfferService'),
    $c
  );
};

$container['AdminOfferController'] = function($c)
{
  $offerUpdateService = new \Broker\Domain\Service\OfferUpdateService(
    new PartnerDataMapperRepository(),
    new PartnerDeliveryGateway(),
    new PartnerRequestFactory(),
    $c->get('PartnerRequestsService'),
    $c->get('PartnerResponseService')
  );

  return new \App\Controller\Admin\AdminOfferController(
    $offerUpdateService,
    $c->get('RepositoryFactory')->createGateway($c->get('db'), 'Offer'),
    $c
  );
};

$container['LoginController'] = function ($c)
{
  $authService = new \App\Base\Components\GoogleAuthenticator();
  $userRepository = $c->get('RepositoryFactory')->createGateway($c->get('db'), 'User');
  $authHandler = new \App\Base\Components\AuthHandler($authService, $userRepository, $c);
  return new \App\Controller\Admin\LoginController($c, $authHandler);
};

$brokerSettings = $container->get('settings')['broker'];
$brokerSettings['logger'] = array_merge($container->get('settings')['logger'], $brokerSettings['logger']);

Config::getInstance()->setConfig($brokerSettings);