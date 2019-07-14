<?php

declare(strict_types=1);

namespace Api\ReadModel\User;

interface UserReadRepository
{
    public function find(string $id): ?User;
}
