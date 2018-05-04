<?php
// Application middleware
use App\Middleware\LanguageSwitcher;
// e.g: $app->add(new \Slim\Csrf\Guard);

$app->add(new \App\Middleware\Session([
  'name' => 'broker',
  'autorefresh' => true,
  'lifetime' => '20 minutes'
]));

$app->add(new LanguageSwitcher($app));

$app->add($container->get('csrf'));