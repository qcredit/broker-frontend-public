<?php

use Slim\Http\Request;
use Slim\Http\Response;
use App\Controllers\UserController;
// Routes

$app->map(['GET', 'POST'], '/application', 'ApplicationController:indexAction');
$app->get('/application/{hash}', 'ApplicationController:offerListAction');
$app->get('/', 'HomeController:indexAction');

$app->get('/admin', 'AdminController:indexAction');
$app->get('/admin/partners', 'AdminController:partnersAction');
$app->get('/admin/partner/{id}', 'AdminController:partnerAction');
$app->get('/admin/applications', 'AdminController:applicationsAction');
$app->get('/admin/application/{id}', 'AdminController:applicationAction');
$app->get('/admin/users', 'AdminController:usersAction');

$app->map(['GET', 'POST'], '/admin/login', 'AdminController:loginAction');