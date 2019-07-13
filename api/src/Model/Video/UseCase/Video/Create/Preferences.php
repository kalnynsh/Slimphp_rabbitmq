<?php

declare(strict_types=1);

namespace Api\Model\UseCase\Video\Create;

class Preferences
{
    /**
     * @var Size
     */
    public $thumbnailSize;

    /**
     * @var Size[]
     */
    public $videoSizes;

    /**
     * @Format[]
     */
    public $videoFormats;
}
