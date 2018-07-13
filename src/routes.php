<?php

use Slim\Http\Request;
use Slim\Http\Response;
use App\Middleware\Authenticator;
use App\Middleware\PartnerAuthenticator;
// Routes

$app->group('/', function() {
  $this->map(['GET', 'POST'], _('application'), 'ApplicationController:indexAction')->add(new \App\Middleware\ApplicationPost($this));
  $this->get('application/schema', 'ApplicationController:schemaAction');
  $this->get('application/status', 'ApplicationController:statusAction');
  $this->get(sprintf('%s/%s/{hash}', _('application'), _('resume')), 'ApplicationController:resumeAction');
  $this->get(sprintf('%s/{hash}', _('application')), 'ApplicationController:offersAction');
  //$this->map(['GET', 'POST'], '/application/{hash}/offer/{id}', 'ApplicationController:selectOfferAction');
  $this->get('', 'HomeController:indexAction');
  $this->get('language', 'HomeController:languageAction');
  $this->get(_('about'), 'AboutController:indexAction');
  $this->map(['GET', 'POST'], _('contact'), 'ContactController:indexAction');
  $this->get(_('privacy-policy'), 'PrivacyController:indexAction');
  $this->get(_('cookies-policy'), 'CookieController:indexAction');
  $this->get(_('terms-and-conditions'), 'TermsController:indexAction');
  $this->get(_('blog'), 'BlogController:indexAction');
  $this->get(sprintf('%s/%s/{tag}', _('blog'), _('tag')), 'BlogController:tagAction');
  $this->get(sprintf('%s/{slug}', _('blog')), 'BlogController:viewAction');
  $this->get('test', 'TestController:mailAction');

  $this->group('office', function() {
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

$app->post('/api/partner/update-offer', 'ApiController:updateAction')->add(new PartnerAuthenticator($app));

$app->map(['GET', 'POST'], '/office/login', 'LoginController:loginAction');
$app->get('/office/logout', 'LoginController:logoutAction');
