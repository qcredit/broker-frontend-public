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
            'name' => 'WEBSITE',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : '/var/log/apache2/broker-frontend-public_debug.log',
            'level' => \Monolog\Logger::DEBUG,
            'processor' => [
              new \Monolog\Processor\UidProcessor()
            ]
        ],
        'broker' => [
          'environment' => getenv('ENV_TYPE') ? getenv('ENV_TYPE') : 'developer',
          'logger' => [
            'name' => 'BROKER',
            'loggerClass' => 'App\Base\Logger',
/*            'path' => __DIR__ . '/../logs/broker.log',*/
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
            'production' => [
              'driver' => 'pdo_mysql',
              'host' => 'public57-prod.cpk3qjynnaba.eu-west-1.rds.amazonaws.com',
              'dbname' => 'broker_frontend_public',
              'user' => 'broker_frontend_public',
              'password' => '5WbdqtcKCmCubyYujG3L',
              'port' => 3306,
              'charset' => 'utf8'
            ],
            'developer' => [
              'driver' => 'pdo_mysql',
              'host' => 'mysql',
              'user' => 'root',
              'password' => 'hobunelasiterveaiat2is',
              'dbname' => 'broker',
              'charset' => 'utf8',
              'port' => 3306
            ],
            "testserver" => [
              "driver" => "pdo_mysql",
              "host" => "public57-test.cpk3qjynnaba.eu-west-1.rds.amazonaws.com",
              "dbname" => "broker_frontend_public_test",
              "user" => "broker_frontend_public_test",
              "password" => "5dMxsq79CDAT4vCNUFr4",
              "port" => 3306,
              "charset" => "utf8"
            ]
          ]
        ],
        'mailer' => [
          'host' => 'smtp.gmail.com',
          'username' => 'hendrik.uibopuu@aasaglobal.com',
          'password' => '',
          'secure' => 'tls',
          'port' => 587,
          'sender' => 'hendrik.uibopuu@aasaglobal.com',
          'senderName' => 'qCredit'
        ],
        'messente' => [
          'senderName' => 'qCredit',
          'sender' => '+48732232358', //dev
          'username' => 'f7303187a44e84450d202debecb507ea',
          'password' => '849556537bdce14bb797b42d32641a36'
        ]
    ],
];