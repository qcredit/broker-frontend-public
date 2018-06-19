<?php
return [
    'settings' => [
        'mainEmail' => 'info@qcredit.pl',
        'defaultLanguage' => 'pl_PL',
        'companyName' => 'Q Credit Sp. z o.o.',
        'baseUrl' => getenv('ENV_TYPE') == 'production' ? 'https://www.qcredit.pl' : (getenv('ENV_TYPE') == 'testserver' ? 'https://www-test.qcredit.pl' : 'http://localhost:8100'),
        'displayErrorDetails' => getenv('ENV_TYPE') == 'production' ? false : true,
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'BROKER',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : '/var/log/apache2/broker-frontend-public_debug.log',
            'level' => getenv('ENV_TYPE') == 'production' ? \Monolog\Logger::INFO : \Monolog\Logger::DEBUG,
            'processor' => [
              new \Monolog\Processor\UidProcessor()
            ],
            'dateFormat' => 'Y-m-d\TH:i:sO'
        ],
        'broker' => [
          'environment' => getenv('ENV_TYPE') ? getenv('ENV_TYPE') : 'developer',
          'logger' => [
            'name' => 'BROKER',
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
          'username' => 'qcredit.test@gmail.com',
          'password' => 'URXpY7xGDL',
          'secure' => 'tls',
          'port' => 587,
          'sender' => 'info@qcredit.pl',
          'senderName' => 'qCredit',
          'apiKey' => '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',
          'apiUrl' => 'https://api.sendgrid.com/v3/mail/send'
        ],
        'messente' => [
          'apiUrl' => 'https://api2.messente.com/send_sms/',
          'senderName' => 'QCredit',
          'sender' => getenv('ENV_TYPE') == 'production' ? '+48732168527' : '+48732232358',
          'whitelist' => [
            '+3725171081',
            '+37253439601'
          ],
          'username' => '301cda77166de101a7751b1f1a6322df',
          'password' => '0197a62dce2ce9db1ab09437197b09b7'
        ]
    ],
];