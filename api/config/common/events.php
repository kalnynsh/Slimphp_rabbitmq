<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Api\Infrastructure\Model\EventDispatcher\SyncEventDispatcher;

return [
    Api\Model\EventDispatcher::class =>
        function (ContainerInterface $container) {
            return new SyncEventDispatcher(
                $container,
                []
            );
        },
];
