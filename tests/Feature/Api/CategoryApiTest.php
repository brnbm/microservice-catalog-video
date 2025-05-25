<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CategoryApiTest extends TestCase
{
    protected $uri = '/api/categories';

    #[Test]
    public function emptyList(): void
    {
        $response = $this->getJson($this->uri);
        $response->assertStatus(200);
    }
}
