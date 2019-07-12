<?php

declare(strict_types=1);

namespace Api\Infrastructure\Model\OAuth\Entity;

use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use Doctrine\ORM\EntityManagerInterface;
use Api\Model\OAuth\Entity\RefreshTokenEntity;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(RefreshTokenEntity::class);
        $this->em = $em;
    }

    public function getNewRefreshToken(): RefreshTokenEntityInterface
    {
        return new RefreshTokenEntity();
    }

    public function persistNewRefreshToken(
        RefreshTokenEntityInterface $refreshTokenEntity
    ): void {
        if ($this->exists($refreshTokenEntity->getIdentifier())) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }

        $this->em->persist($refreshTokenEntity);
        $this->em->flush();
    }

    public function revokeRefreshToken($tokenId): void
    {
        if ($token = $this->repo->find($tokenId)) {
            $this->em->remove($token);
            $this->em->flush();
        }
    }

    public function isRefreshTokenRevoked($tokenId): bool
    {
        return !$this->exists($tokenId);
    }

    private function exists($id): bool
    {
        return
            $this
                ->repo
                ->createQueryBuilder('t')
                ->select('COUNT(t.identifier)')
                ->andWhere('t.identifier = :identifier')
                ->setParameter(':identifier', $id)
                ->getQuery()
                ->getSingleScalarResult()
                    > 0
            ;
    }
}
