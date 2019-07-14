<?php

declare(strict_types=1);

namespace Api\Infrastructure\ReadModel\User;

use Doctrine\ORM\EntityManagerInterface;
use Api\ReadModel\User\UserReadRepository;
use Api\Model\User\Entity\User\User;

class DoctrineUserReadRepository implements UserReadRepository
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
        $this->repo = $em->getRepository(User::class);
        $this->em = $em;
    }

    public function find(string $id): ?User
    {
        return $this->repo->find($id);
    }
}
