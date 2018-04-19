<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 19.04.18
 * Time: 16:09
 */

return [
  "paths" => [
    "migrations" => "%%PHINX_CONFIG_DIR%%/migrations",
    "seeds" => "%%PHINX_CONFIG_DIR%%/migrations/seeds"
  ],
  "environments" => [
    "default_migration_table" => "phinxlog",
    "default_database" => getenv("ENV_TYPE") ? getenv("ENV_TYPE") : "developer",
    "production" => [
      "adapter" => "mysql",
      "host" => "luxco-public.cpk3qjynnaba.eu-west-1.rds.amazonaws.com",
      "name" => "broker_frontend_production",
      "user" => "bf_prod",
      "pass" => "PjkAbyTKTe2dr2qMRQF2",
      "port" => 3306,
      "charset" => "utf8"
    ],
    "developer" => [
      "adapter" => "mysql",
      "host" => "mysql",
      "name" => "broker",
      "user" => "root",
      "pass" => "hobunelasiterveaiat2is",
      "port" => 3306,
      "charset" => "utf8"
    ],
    "testserver" => [
      "adapter" => "mysql",
      "host" => "luxco-public.cpk3qjynnaba.eu-west-1.rds.amazonaws.com",
      "name" => "broker_frontend_test",
      "user" => "bf_test",
      "pass" => "a9HJKvXz4uKRXvfVaBBg",
      "port" => 3306,
      "charset" => "utf8"
    ]
  ],
  "version_order" => "creation"
];