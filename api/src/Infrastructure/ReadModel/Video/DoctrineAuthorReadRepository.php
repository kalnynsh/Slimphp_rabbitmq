<?php

declare(strict_types=1);

namespace Api\Infrastructure\ReadModel\Video;

use Doctrine\ORM\EntityManagerInterface;
use Api\ReadModel\Video\AuthorReadRepository;
use Api\Model\Video\Entity\Author\Author;

class DoctrineAuthorReadRepository implements AuthorReadRepository
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
        $this->repo = $em->getRepository(Author::class);
        $this->em = $em;
    }

    public function find(string $id): ?Author
    {
        return $this->repo->find($id);
    }
}
