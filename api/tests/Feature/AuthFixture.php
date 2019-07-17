<?php

declare(strict_types=1);

namespace Api\Test\Feature;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Api\Test\Builder\User\UserBuilder;
use Api\Model\User\Entity\User\User;
use Api\Model\User\Entity\User\Email;
use Api\Model\User\Entity\User\ConfirmToken;
use Api\Model\OAuth\Entity\ScopeEntity;
use Api\Model\OAuth\Entity\ClientEntity;
use Api\Model\OAuth\Entity\AccessTokenEntity;

class AuthFixture extends AbstractFixture
{
    private $user;
    private $token;

    public function load(ObjectManager $manager): void
    {
        $user =
            (new UserBuilder())
            ->withDate($now = new \DateTimeImmutable())
            ->withEmail(new Email('test@example.com'))
            ->withConfirmToken(
                new ConfirmToken(
                    $token = 'token',
                    $now->modify('+1 day')
                )
            )
            ->build();

        $user->confirmSignup($token, new \DateTimeImmutable());

        $manager->persist($user);

        $this->user = $user;

        $token = new AccessTokenEntity();
        $tokenIdentifier = bin2hex(random_bytes(40));
        $token->setIdentifier($tokenIdentifier);
        $token->setUserIdentifier($user->getId()->getId());
        $token->setExpiryDateTime(new \DateTime('+1 hour'));
        $token->setClient(new ClientEntity('app'));
        $token->addScope(new ScopeEntity('common'));

        $manager->persist($token);
        $manager->flush();

        $this->token = (string)$token->convertToJWT(CryptKeyHelper::get());

        $this->addReference('user', $user);
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
        ];
    }
}
