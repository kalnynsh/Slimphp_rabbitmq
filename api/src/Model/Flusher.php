<?php

declare(strict_types=1);

namespace Api\Model;

use Api\Model\AggregateRoot;

interface Flusher
{
    public function flush(AggregateRoot ...$root): void;
}
