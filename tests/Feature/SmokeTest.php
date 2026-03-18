<?php

namespace Tests\Feature;

use Tests\TestCase;

class SmokeTest extends TestCase
{
    public function test_homepage_does_not_return_server_error(): void
    {
        $response = $this->get('/');

        $this->assertLessThan(500, $response->getStatusCode());
    }
}
