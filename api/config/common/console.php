<?php

use Api\Console\Command;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;

return [
    Command\Amqp\ProduceCommand::class => function (ContainerInterface $container) {
        return new Command\Amqp\ProduceCommand(
            $container->get(AMQPStreamConnection::class)
        );
    },
    Command\Amqp\ConsumeCommand::class => function (ContainerInterface $container) {
        return new Command\Amqp\ConsumeCommand(
            $container->get(AMQPStreamConnection::class)
        );
    },

    'config' => [
        'console' => [
            'commands' => [
                Command\Amqp\ProduceCommand::class,
                Command\Amqp\ConsumeCommand::class,
            ],
        ],
    ],
];
