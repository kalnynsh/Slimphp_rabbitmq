<?php

declare(strict_types=1);

namespace Api\Infrastructure\ReadModel\Video;

use Doctrine\ORM\EntityManagerInterface;
use Api\ReadModel\Video\VideoReadRepository;
use Api\Model\Video\Entity\Video\Video;

class DoctrineVideoReadRepository implements VideoReadRepository
{
    /**
     * @var \Doctrine\ORM\Entity\EntityRepository
     */
    private $repo;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Video::class);
        $this->em = $em;
    }

    public function find(string $authorId, string $id): ?Video
    {
        return $this
            ->repo
            ->findOneBy([
                'author' => $authorId,
                'id' => $id
            ]);
    }

    public function allByAuthor(string $authorId): array
    {
        return $this
            ->repo
            ->createQueryBuilder('v')
            ->andWhere('v.author = :author')
            ->setParameter(':author', $authorId)
            ->orderBy('v.createDate', 'desc')
            ->getQuery()
            ->getResult();
    }
}
