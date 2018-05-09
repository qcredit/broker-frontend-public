<?php

use Slim\Http\Request;
use Slim\Http\Response;
use App\Middleware\Authenticator;
// Routes

$app->group('/', function() {
  $this->map(['GET', 'POST'], 'application', 'ApplicationController:indexAction');
  $this->get('application/schema', 'ApplicationController:schemaAction');
  $this->get('application/status', 'ApplicationController:statusAction');
  $this->get('application/{hash}', 'ApplicationController:offersAction');
  $this->map(['GET', 'POST'], '/application/{hash}/offer/{id}', 'ApplicationController:selectOfferAction');
  $this->get('', 'HomeController:indexAction');
  $this->get('language', 'HomeController:languageAction');
  $this->get('about', 'AboutController:indexAction');
  $this->get('contact', 'ContactController:indexAction');
  $this->get('terms', 'TermsController:indexAction');
  $this->get('test', 'TestController:mailAction');

  $this->group('admin', function() {
    $this->get('', 'AdminController:indexAction');
    $this->map(['GET', 'POST'], '/partners/new', 'PartnerController:newAction');
    $this->map(['GET', 'POST'], '/partners/update/{id}', 'PartnerController:updateAction');
    $this->get('/partners/{id}', 'PartnerController:viewAction');
    $this->get('/partners', 'PartnerController:indexAction');
    $this->get('/applications', 'AdminApplicationController:indexAction');
    $this->get('/applications/{id}', 'AdminApplicationController:viewAction');
    $this->get('/offers/update/{id}', 'AdminOfferController:updateAction');

    $this->get('/users', 'UserController:indexAction');
    $this->map(['GET', 'POST'], '/users/new', 'UserController:newAction');
    $this->get('/users/delete/{id}', 'UserController:deleteAction');
  })->add(new Authenticator($this));
})->add($container->get('csrf'));

$app->post('/api/aasa', 'ApiController:aasaUpdateAction');

$app->map(['GET', 'POST'], '/admin/login', 'LoginController:loginAction');
$app->get('/admin/logout', 'LoginController:logoutAction');