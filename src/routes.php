<?php

use Slim\Http\Request;
use Slim\Http\Response;
use App\Controller\UserController;
use App\Middleware\Authenticator;
// Routes

$app->map(['GET', 'POST'], '/application', 'ApplicationController:indexAction');
$app->get('/application/schema', 'ApplicationController:schemaAction');
$app->get('/application/status', 'ApplicationController:statusAction');
$app->get('/application/{hash}', 'ApplicationController:offersAction');
$app->map(['GET', 'POST'], '/application/{hash}/offer/{id}', 'ApplicationController:selectOfferAction');
$app->get('/', 'HomeController:indexAction');
$app->get('/about', 'AboutController:indexAction');
$app->get('/contact', 'ContactController:indexAction');
$app->get('/terms', 'TermsController:indexAction');

$app->group('/admin', function() {
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
})->add(new Authenticator($app));

$app->map(['GET', 'POST'], '/admin/login', 'LoginController:loginAction');
$app->get('/admin/logout', 'LoginController:logoutAction');