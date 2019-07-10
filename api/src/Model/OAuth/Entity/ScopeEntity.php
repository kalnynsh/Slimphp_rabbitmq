<?php

declare(strict_types=1);

namespace Api\Model\OAuth\Entity;

use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

class ScopeEntity implements ScopeEntityInterface
{
    use EntityTrait;

    public function __construct(string $identifier)
    {
        $this->identifier = $identifier;
    }

    public function jsonSerialize()
    {
        return $this->getIdentifier();
    }
}
