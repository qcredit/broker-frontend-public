<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 19.04.18
 * Time: 16:09
 */
$env = getenv("ENV_TYPE") ? getenv("ENV_TYPE") : "developer";
$settings = require_once(__DIR__ . '/src/settings.php');
$db = $settings['settings']['doctrine']['connection'];

return [
  "paths" => [
    "migrations" => "%%PHINX_CONFIG_DIR%%/migrations",
    "seeds" => "%%PHINX_CONFIG_DIR%%/migrations/seeds"
  ],
  "environments" => [
    "default_migration_table" => "phinxlog",
    "default_database" => $env,
    "production" => [
      "adapter" => "mysql",
      "host" => $db["production"]["host"],
      "name" => $db["production"]["dbname"],
      "user" => $db["production"]["user"],
      "pass" => $db["production"]["pass"],
      "port" => $db["production"]["port"],
      "charset" => $db["production"]["charset"]
    ],
    "developer" => [
      "adapter" => "mysql",
      "host" => $db["developer"]["host"],
      "name" => $db["developer"]["dbname"],
      "user" => $db["developer"]["user"],
      "pass" => $db["developer"]["pass"],
      //"port" => $db["developer"]["port"],
      "charset" => $db["developer"]["charset"]
    ],
    "testserver" => [
      "adapter" => "mysql",
      "host" => $db["testserver"]["host"],
      "name" => $db["testserver"]["dbname"],
      "user" => $db["testserver"]["user"],
      "pass" => $db["testserver"]["pass"],
      "port" => $db["testserver"]["port"],
      "charset" => $db["testserver"]["utf8"]
    ]
  ],
  "version_order" => "creation"
];