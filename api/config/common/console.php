<?php

use Psr\Log\LoggerInterface;
use Psr\Container\ContainerInterface;
use Kafka\Producer;
use Kafka\ConsumerConfig;
use Api\Console\Command;

return [
    Command\Kafka\ProduceCommand::class => function (ContainerInterface $container) {
        return new Command\Kafka\ProduceCommand(
            $container->get(Producer::class)
        );
    },
    Command\Kafka\ConsumeCommand::class => function (ContainerInterface $container) {
        return new Command\Kafka\ConsumeCommand(
            $container->get(LoggerInterface::class),
            $container->get(ConsumerConfig::class)
        );
    },

    'config' => [
        'console' => [
            'commands' => [
                Command\Kafka\ProduceCommand::class,
                Command\Kafka\ConsumeCommand::class,
            ],
        ],
    ],
];
