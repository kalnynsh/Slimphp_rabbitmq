<?php

declare(strict_types=1);

namespace Api\Infrastructure\Model\OAuth\Entity;

use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use Doctrine\ORM\EntityManagerInterface;
use Api\Model\OAuth\Entity\AuthCodeEntity;

class AuthCodeRepository implements AuthCodeRepositoryInterface
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(AuthCodeEntity::class);
        $this->em = $em;
    }

    public function getNewAuthCode(): AuthCodeEntityInterface
    {
        return new AuthCodeEntity();
    }

    public function persistNewAuthCode(
        AuthCodeEntityInterface $authCodeEntity
    ): void {
        if ($this->exists($authCodeEntity->getIdentifier())) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }

        $this->em->persist($authCodeEntity);
        $this->em->flush();
    }

    public function revokeAuthCode($authCodeId): void
    {
        if ($authCode = $this->repo->find($authCodeId)) {
            $this->em->remove($authCode);
            $this->em->flush();
        }
    }

    public function isAuthCodeRevoked($authCodeId): bool
    {
        return !$this->exists($authCodeId);
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
