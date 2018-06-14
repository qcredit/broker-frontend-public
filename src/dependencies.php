<?php
use Broker\System\Config;
use App\Base\Repository\PartnerDataMapperRepository;
use Broker\Domain\Service\NewApplicationService;
use Broker\Domain\Factory\ApplicationFactory;
use Broker\Domain\Service\PartnerResponseService;
use Broker\Domain\Factory\OfferFactory;
use Broker\Domain\Factory\PartnerRequestFactory;
use Syslogic\DoctrineJsonFunctions\Query\AST\Functions\Mysql as DqlFunctions;
use Broker\Domain\Service\SendPartnerRequestsService;

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

$container['session'] = function() {
  return new \SlimSession\Helper;
};

$container['cookies'] = function()
{
  return new \Slim\Http\Cookies();
};

$container['flash'] = function() {
  return new \Slim\Flash\Messages();
};

$container['csrf'] = function()
{
  $storage = null;
  return new \Slim\Csrf\Guard('csrf', $storage, null, 200, 16, true);
};

$container['view'] = function($container) {
  $view = new \Slim\Views\Twig(dirname(__DIR__) . '/templates');

  // Instantiate and add Slim specific extension
  $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
  $view->addExtension(new Twig_Extensions_Extension_I18n());
  $view->addExtension(new Twig_Extensions_Extension_Intl());
  $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
  $view->addExtension(new \App\Component\CsrfExtension($container->get('csrf')));

  if (!preg_match('/\/application\/+\S.*$/', $container->get('request')->getUri()->getPath()))
  {
    $view->getEnvironment()->addGlobal('currentUrl', $container->get('request')->getUri());
  }
  else
  {
    $view->getEnvironment()->addGlobal('currentUrl', '/application');
  }

  $view->getEnvironment()->addGlobal('environment', getenv('ENV_TYPE'));

  return $view;
};

$container['db'] = function($container) {
  $settings = $container->get('settings')['doctrine'];

  $config = \Doctrine\ORM\Tools\Setup::createYAMLMetadataConfiguration([dirname(__DIR__) . '/src/Base/Persistence/Doctrine/Mapping'], true);
  $config->addCustomStringFunction(DqlFunctions\JsonExtract::FUNCTION_NAME, DqlFunctions\JsonExtract::class);
  $config->addCustomStringFunction(DqlFunctions\JsonSearch::FUNCTION_NAME, DqlFunctions\JsonSearch::class);
  $config->addCustomStringFunction(DqlFunctions\JsonContains::FUNCTION_NAME, DqlFunctions\JsonContains::class);
  $config->addCustomStringFunction(DqlFunctions\JsonContainsPath::FUNCTION_NAME, DqlFunctions\JsonContainsPath::class);

  $dbConf = getenv("ENV_TYPE") ? getenv("ENV_TYPE") : "developer";

  $entityManager = \Doctrine\ORM\EntityManager::create($settings['connection'][$dbConf], $config);

  return $entityManager;
};

$container['RepositoryFactory'] = function($c)
{
  return new \App\Base\Factory\RepositoryFactory();
};

$container['PartnerResponseFactory'] = function($c)
{
  return new \Broker\Domain\Factory\PartnerResponseFactory();
};

$container['UserRepository'] = function($container) {
  return $container->get('RepositoryFactory')->createGateway($container->get('db'), 'User');
};

$container['PartnerRepository'] = function($container)
{
  return $container->get('RepositoryFactory')->createGateway($container->get('db'), 'Partner');
};

$container['ApplicationRepository'] = function($container)
{
  return $container->get('RepositoryFactory')->createGateway($container->get('db'), 'Application');
};

$container['OfferRepository'] = function($container)
{
  return $container->get('RepositoryFactory')->createGateway($container->get('db'), 'Offer');
};

$container['AdminController'] = function($c)
{
  return new \App\Controller\Admin\AdminController($c);
};

$container['TestController'] = function($c)
{
  return new \App\Controller\TestController($c);
};

$container['PartnerController'] = function($c) {
  $partnerRepository = $c->get('RepositoryFactory')->createGateway($c->get('db'), 'Partner');

  $partnerDataLoader = $c->get('PartnerExtraDataLoader');
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
  $validator = new \App\Base\Validator\UserValidator($c->get('BrokerInstance'));
  return new \App\Controller\Admin\UserController($userRepository, $userFactory, $validator, $c);
};

$container['HomeController'] = function($c)
{
  return new \App\Controller\HomeController($c);
};
$container['AboutController'] = function($c)
{
  $view = $c->get('view');
  return new \App\Controller\AboutController($view);
};
$container['ContactController'] = function($c)
{
  $contactForm = new \App\Model\ContactForm($c, $c->get('BrokerInstance'), new \App\Base\Repository\MessageTemplateRepository($c, new \Broker\Domain\Factory\MessageFactory()), $c->get('MessageDeliveryService'));
  return new \App\Controller\ContactController($c, $contactForm);
};
$container['PrivacyController'] = function($c)
{
  $view = $c->get('view');
  return new \App\Controller\PrivacyController($view);
};
$container['TermsController'] = function($c)
{
  $view = $c->get('view');
  return new \App\Controller\TermsController($view);
};
$container['CookieController'] = function($c)
{
  $view = $c->get('view');
  return new \App\Controller\CookieController($view);
};

$container['ApiController'] = function($c)
{
  return new \App\Controller\ApiController($c->get('PartnerUpdateService'), $c);
};

$container['MessageTemplateRepository'] = function($c)
{
  return new \App\Base\Repository\MessageTemplateRepository($c, new \Broker\Domain\Factory\MessageFactory());
};

$container['PartnerDataMapperRepository'] = function($c)
{
  return new PartnerDataMapperRepository();
};

$container['PartnerExtraDataLoader'] = function($c)
{
  return new \App\Base\Repository\PartnerExtraDataLoader($c->get('PartnerDataMapperRepository'));
};

$container['SendPartnerRequestsService'] = function($c)
{
  return new SendPartnerRequestsService(
    $c->get('BrokerInstance'),
    $c->get('MessageDeliveryService')
  );
};

$container['PartnerResponseService'] = function($c)
{
  return new PartnerResponseService(
    $c->get('BrokerInstance'),
    new OfferFactory(),
    $c->get('RepositoryFactory')->createGateway($c->get('db'), 'Offer')
  );
};

$container['MessageDeliveryService'] = function ($c)
{
  return new \Broker\Domain\Service\MessageDeliveryService(
    $c->get('BrokerInstance'),
    new \App\Base\Factory\MessageDeliveryStrategyFactory($c)
  );
};

$container['ChooseOfferService'] = function($c)
{
  return new \Broker\Domain\Service\ChooseOfferService(
    $c->get('BrokerInstance'),
    $c->get('SendPartnerRequestsService'),
    $c->get('PartnerResponseService'),
    new PartnerRequestFactory(),
    new \App\Base\Validator\SchemaValidator(),
    $c->get('MessageDeliveryService')
  );
};

$container['PartnerUpdateService'] = function($c)
{
  return new \Broker\Domain\Service\PartnerUpdateService(
    $c->get('BrokerInstance'),
    $c->get('OfferRepository'),
    new \App\Base\Validator\SchemaValidator()
  );
};

$container['ApplicationValidator'] = function($c)
{
  $schemaValidator = new \App\Base\Validator\SchemaValidator();
  $applicationValidator = new \App\Base\Validator\ApplicationValidator($c->get('BrokerInstance'), $c->get('PartnerRepository'), $schemaValidator);

  return $applicationValidator;
};

$container['PostApplicationService'] = function($c)
{
  $factory = $c->get('RepositoryFactory');
  $appRepository = $factory->createGateway($c->get('db'), 'Application');
  $offerRepository = $factory->createGateway($c->get('db'), 'Offer');

  $newApplicationService = new NewApplicationService(
    $c->get('BrokerInstance'),
    new ApplicationFactory(),
    $appRepository,
    $c->get('ApplicationValidator')
  );

  $prepareService = new \Broker\Domain\Service\PreparePartnerRequestsService(
    $c->get('BrokerInstance'),
    $c->get('SendPartnerRequestsService'),
    $c->get('PartnerResponseService'),
    new PartnerRequestFactory(),
    $c->get('MessageDeliveryService'),
    $c->get('MessageTemplateRepository')
  );

  $createRequestsService = new \Broker\Domain\Service\CreatePartnerRequestsService($c->get('BrokerInstance'), new PartnerRequestFactory(), $c->get('PartnerRepository'));

  return new \Broker\Domain\Service\PostApplicationService(
    $c->get('BrokerInstance'),
    $newApplicationService,
    $createRequestsService,
    $c->get('SendPartnerRequestsService'),
    $c->get('PartnerResponseService'),
    $c->get('MessageTemplateRepository'),
    $c->get('MessageDeliveryService')
    );
};

$container['ApplicationController'] = function ($c)
{
  $factory = $c->get('RepositoryFactory');
  $appRepository = $factory->createGateway($c->get('db'), 'Application');
  $offerRepository = $factory->createGateway($c->get('db'), 'Offer');

  return new \App\Controller\ApplicationController(
    $appRepository,
    $offerRepository,
    $c->get('ChooseOfferService'),
    $c->get('PostApplicationService'),
    $c
  );
};

$container['AdminOfferController'] = function($c)
{
  $offerUpdateService = new \Broker\Domain\Service\OfferUpdateService(
    $c->get('BrokerInstance'),
    new PartnerRequestFactory(),
    $c->get('SendPartnerRequestsService'),
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
  $authService = new \App\Component\GoogleAuthenticator();
  $userRepository = $c->get('RepositoryFactory')->createGateway($c->get('db'), 'User');
  $authHandler = new \App\Component\AuthHandler($authService, $userRepository, $c);
  return new \App\Controller\Admin\LoginController($c, $authHandler);
};

$container['BlogController'] = function($c)
{
  $blog = new \Aasa\CommonWebSDK\BlogServiceAWS();
  \Aasa\CommonWebSDK\Configuration::getInstance()->init(6, 'pl', 'blog');
  return new \App\Controller\BlogController($blog, $c);
};

$container['FormBuilder'] = function($c)
{
  return new \App\Component\FormBuilder($c->get('PartnerRepository'), new \App\Component\SchemaHelper());
};

$container['EventManager'] = function($c)
{
  $eventManager = new \Broker\System\Event\EventManager();
  $eventManager->addListener(\Broker\Domain\Interfaces\Service\NewApplicationServiceInterface::EVENT_BEFORE_RUN, new \App\Base\Event\BeforeNewApplicationServiceListener());

  return $eventManager;
};

$container['notFoundHandler'] = function($c)
{
  return function($request, $response) use ($c)
  {
    $c['view']->render($response, '404.twig');

    return $c['response']
      ->withStatus(404)
      ->withHeader('Content-Type', 'text/html');
  };
};

$container['BrokerInstance'] = function($c)
{
  $brokerSettings = $c->get('settings')['broker'];
  $brokerSettings['logger'] = array_merge($c->get('settings')['logger'], $brokerSettings['logger']);

  return new \Broker\System\BrokerInstance(new \Broker\System\NewConfig(), new \App\Base\Logger($brokerSettings['logger']), $c->get('EventManager'));
};
