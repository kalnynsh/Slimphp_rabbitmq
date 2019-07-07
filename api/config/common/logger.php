<?php

declare(strict_types=1);

use Psr\Log\LoggerInterface;
use Psr\Container\ContainerInterface;
use Api\Infrastructure\Framework\ErrorHandler\LogPhpHandler;
use Api\Infrastructure\Framework\ErrorHandler\LogHandler;

return [
    LoggerInterface::class => function (ContainerInterface $container) {
        $config = $container->get('config')['logger'];
        $logger = new \Monolog\Logger('API');
        $logger->pushHandler(new \Monolog\Handler\SteamHandler($config['file']));

        return $logger;
    },

    'errorHandler' => function (ContainerInterface $container) {
        return new LogHandler(
            $container->get(LoggerInterface::class),
            $container->get('settings')['displayErrorDetails']
        );
    },

    'phpErrorHandler' => function (ContainerInterface $container) {
        return new LogPhpHandler(
            $container->get(LoggerInterface::class),
            $container->get('settings')['displayErrorDetails']
        );
    },

    'config' => [
        'logger' => [
            'file' => 'var/log/app.log',
        ],
    ],
];
