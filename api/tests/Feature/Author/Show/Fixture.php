<?php

declare(strict_types=1);

namespace Api\Test\Feature\Author\Show;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Api\Model\Video\Entity\Author\AuthorId;
use Api\Model\Video\Entity\Author\Author;
use Api\Model\User\Entity\User\User;

class Fixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        /** @var User $user */
        $user = $this->getReference('user');

        $author = new Author(
            new AuthorId($user->getId()->getId()),
            'Test Author'
        );

        $manager->persist($author);
        $manager->flush();
    }
}
