<?php

declare(strict_types=1);

namespace Api\Http\Action\Author\Video;

use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Api\ReadModel\Video\VideoReadRepository;
use Api\Model\Video\Entity\Video\Video;
use Api\Model\Video\Entity\Video\File;
use Api\Http\VideoUrl;

class ShowAction implements RequestHandlerInterface
{
    /** @property VideoReadRepository $videos */
    private $videos;

    /** @property VideoUrl $url - Base video URL */
    private $url;

    public function __construct(
        VideoReadRepository $videos,
        VideoUrl $url
    ) {
        $this->videos = $videos;
        $this->url = $url;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $authorId = $request->getAttribute('oauth_user_id');
        $id = $request->getAttribute('id');

        if (!$video = $this->videos->find($authorId, $id)) {
            return new JsonResponse([], 404);
        }

        return new JsonResponse($this->serialize($video));
    }

    private function serialize(Video $video): array
    {
        return [
            'id' => $video->getId()->getId(),
            'name' => $video->getName(),
            'files' => array_map(function (File $file) {
                return [
                    'url' => $this->url->url($file->getPath()),
                    'format' => $file->getFormat(),
                    'size' => [
                        'width' => $file->getSize()->getWidth(),
                        'height' => $file->getSize()->getHeight(),
                    ],
                ];
            }, $video->getFiles()),
        ];
    }
}
