<?php

declare(strict_types=1);

namespace Api\Model\Video\UseCase\Video\Create;

use Api\Model\Video\Service\Processor\Size;
use Api\Model\Video\Service\Processor\Format;

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
