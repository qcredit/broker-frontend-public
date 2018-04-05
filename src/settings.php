<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'broker' => [
          'logger' => [
            'loggerClass' => 'App\Base\Logger',
            'logFile' => __DIR__ . '/../logs/broker.log',
            'logLevel' => 100
          ]
        ],
        'doctrine' => [
          'meta' => [
            'entity_path' => [
              'src/models'
            ],
            'auto_generate_proxies' => true,
            'proxy_dir' =>  __DIR__.'/../cache/proxies',
            'cache' => null,
          ],
          'connection' => [
            'driver' => 'pdo_mysql',
            'host' => 'mysql',
            'user' => 'root',
            'password' => 'hobunelasiterveaiat2is',
            'dbname' => 'broker',
          ]
        ]
    ],
];
