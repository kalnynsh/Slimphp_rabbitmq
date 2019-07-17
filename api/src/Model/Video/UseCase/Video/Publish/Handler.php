<?php

declare(strict_types=1);

namespace Api\Model\Video\UseCase\Video\Publish;

use Api\Model\Video\Entity\Video\VideoRepository;
use Api\Model\Video\Entity\Video\VideoId;
use Api\Model\Flusher;

class Handler
{
    private $videos;
    private $flusher;

    public function __construct(
        VideoRepository $videos,
        Flusher $flusher
    ) {
        $this->videos = $videos;
        $this->flusher = $flusher;
    }

    public function handler(Command $command): void
    {
        $video = $this->videos->get(new VideoId($command->id));

        $video->publish(new \DateTimeImmutable());

        $this->flusher->flush($video);
    }
}
