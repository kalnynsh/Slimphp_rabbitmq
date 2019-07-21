<?php

declare(strict_types=1);

namespace Api\Infrastructure\Model\EventDispatcher\Listener\Video;

use Kafka\Producer;
use Api\Model\Video\Entity\Video\Event\VideoCreated;

class VideoCreatedListener
{
    /** @property Producer $producer */
    private $producer;

    public function __construct(Producer $producer)
    {
        $this->producer= $producer;
    }

    public function __invoke(VideoCreated $event)
    {
        $this->procuder->send([
            [
                'topic' => 'notifications',
                'value' => json_encode([
                    'type' => 'notification',
                    'user_id' => $event->author->getId(),
                    'message' => 'Video created',
                ]),
                'key' => '',
            ]
        ]);
    }
}
