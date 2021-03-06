<?php

declare(strict_types=1);

namespace Api\Model\Video\Entity\Video;

use Webmozart\Assert\Assert;
use Ramsey\Uuid\Uuid;

class VideoId
{
    private $id;

    public function __construct(string $id)
    {
        Assert::notEmpty($id);
        $this->id = $id;
    }

    public static function next(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
