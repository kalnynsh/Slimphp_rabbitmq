<?php

declare(strict_types=1);

namespace Api\Test\Feature\Author\Show;

use Api\Test\Feature\WebTestCase;
use Api\Test\Feature\AuthFixture;

class NoneTest extends WebTestCase
{
    protected function setUp(): void
    {
        $this->loadFixtures([
            'auth' => AuthFixture::class,
        ]);

        parent::setUp();
    }

    public function testGuest(): void
    {
        $response = $this->get('/author');
        self::assertEquals(401, $response->getStatusCode());
        // HTTP 401 Unauthorized
    }

    public function testSuccess(): void
    {
        $auth = $this->getAuth();
        $response = $this->get('/author', $auth->getHeaders());

        self::assertEquals(204, $response->getStatusCode());
        // HTTP 204 No Content
    }

    private function getAuth(): AuthFixture
    {
        return $this->getFixture('auth');
    }
}
