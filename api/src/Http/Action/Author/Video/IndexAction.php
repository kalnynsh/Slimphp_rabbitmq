<?php

declare(strict_types=1);

namespace Api\Http\Action\Author\Video;

use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Api\ReadModel\Video\VideoReadRepository;
use Api\ReadModel\Video\AuthorReadRepository;
use Api\Model\Video\Entity\Video\Video;
use Api\Http\VideoUrl;

class IndexAction implements RequestHandlerInterface
{
    /** @property AuthorReadRepository $authors  */
    private $authors;

    /** @property VideoReadRepository $videos */
    private $videos;

    /** @property VideoUrl $url - Base video URL */
    private $url;

    public function __construct(
        AuthorReadRepository $authors,
        VideoReadRepository $videos,
        VideoUrl $url
    ) {
        $this->authors = $authors;
        $this->videos = $videos;
        $this->url = $url;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (!$author = $this->authors->find(
            $request->getAttribute('oauth_user_id')
        )) {
            return new JsonResponse([], 403);
        }

        $allVideos = $this->videos->allByAuthor($author->getId()->getId());

        return new JsonResponse([
            'count' => \count($allVideos),
            'data' => array_map([$this, 'serialize'], $allVideos),
        ]);
    }

    private function serialize(Video $video): array
    {
        return [
            'id' => $video->getId()->getId(),
            'name' => $video->getName(),
            'thumbnail' => [
                'url' => $this->url->url($video->getThumbnail()->getPath()),
                'width' => $video->getThumbnail()->getSize()->getWidth(),
                'height' => $video->getThumbnail()->getSize()->getHeight(),
            ]
        ];
    }
}
