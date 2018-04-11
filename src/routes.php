<?php

use Slim\Http\Request;
use Slim\Http\Response;
use App\Controllers\UserController;
// Routes

$app->map(['GET', 'POST'], '/application', 'ApplicationController:indexAction');
$app->get('/application/{hash}', 'ApplicationController:offersAction');
$app->get('/', 'HomeController:indexAction');

$app->get('/admin', 'AdminController:indexAction');
$app->map(['GET', 'POST'], '/admin/partners/new', 'PartnerController:newAction');
$app->map(['GET', 'POST'], '/admin/partners/update/{id}', 'PartnerController:updateAction');
$app->get('/admin/partners/{id}', 'PartnerController:viewAction');
$app->get('/admin/partners', 'PartnerController:indexAction');
$app->get('/admin/applications', 'AdminApplicationController:indexAction');
$app->get('/admin/applications/{id}', 'AdminApplicationController:viewAction');
$app->get('/admin/offers/update/{id}', 'AdminOfferController:updateAction');

$app->get('/admin/users', 'AdminController:usersAction');

$app->map(['GET', 'POST'], '/admin/login', 'AdminController:loginAction');