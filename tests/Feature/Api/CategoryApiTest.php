<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;

class CategoryApiTest extends TestCase
{
    protected $endpoint = '/api/categories';

    #[Test]
    public function emptyList(): void
    {
        $response = $this->getJson($this->endpoint);
        $response->assertStatus(200);
    }

    #[Test]
    public function listAll(): void
    {
        Category::factory()->count(30)->create();

        $numberPage = 2;
        $response = $this->getJson("$this->endpoint?page=$numberPage");

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals($numberPage, $response->json('meta.current_page'));
    }

    #[Test]
    public function listNotFound(): void
    {
        $response = $this->getJson("$this->endpoint/fakeValue");
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
