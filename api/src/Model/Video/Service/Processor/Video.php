<?php

declare(strict_types=1);

namespace Api\Model\Video\Service\Processor;

class Video
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var Format
     */
    private $format;

    /**
     * @var Size
     */
    private $size;

    public function __construct(
        string $path,
        Format $format,
        Size $size
    ) {
        $this->path = $path;
        $this->format = $format;
        $this->size = $size;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getFormat(): Format
    {
        return $this->format;
    }

    public function getSize(): Size
    {
        return $this->size;
    }
}
