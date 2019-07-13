<?php

declare(strict_types=1);

namespace Api\Model\Video\Service\Processor;

class Size
{
    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    public function __construct(int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function lessThen(self $size): bool
    {
        return $this->getHeight() < $size->getHeight();
    }

    public function lessOrEqual(self $size): bool
    {
        return $this->getHeight() <= $size->getHeight();
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }
}
