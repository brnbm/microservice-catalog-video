<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;

class CategoryApiTest extends TestCase
{
    protected $uri = '/api/categories';

    #[Test]
    public function emptyList(): void
    {
        $response = $this->getJson($this->uri);
        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
    }

    #[Test]
    public function listAll(): void
    {
        Category::factory()->count(30)->create();

        $response = $this->getJson($this->uri);
        $response->assertStatus(Response::HTTP_OK);
    }

    #[Test]
    public function listPaginate(): void
    {
        Category::factory()->count(30)->create();

        $numberPage = 2;
        $response = $this->getJson("$this->uri?page=$numberPage");

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals($numberPage, $response->json('meta.current_page'));
        $this->assertEquals(30, $response->json('meta.total'));
    }

    #[Test]
    public function listNotFound(): void
    {
        $response = $this->getJson("$this->uri/fakeValue");
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function list(): void
    {
        $category = Category::factory()->create();

        $response = $this->getJson("$this->uri/{$category->id}");
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals($category->id, $response->json('data.id'));
    }

    #[Test]
    public function store(): void
    {
        $data = ['name' => 'Category Test'];

        $response = $this->postJson($this->uri, $data);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    #[Test]
    public function validationsUpdate(): void
    {
        $data = [];

        $response = $this->putJson("{$this->uri}/fakeValue", $data);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    #[Test]
    public function notFoundUpdate(): void
    {
        $data = ['name' => 'Updated Category Name'];

        $response = $this->putJson("{$this->uri}/fakeValue", $data);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function update(): void
    {
        $category = Category::factory()->create();

        $data = ['name' => 'Updated Category Name'];

        $response = $this->putJson("{$this->uri}/{$category->id}", $data);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals($data['name'], $response->json('data.name'));
    }

    #[Test]
    public function notFoundDestroy(): void
    {
        $response = $this->deleteJson("{$this->uri}/fakeValue");
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function destroy(): void
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("{$this->uri}/{$category->id}");
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
            'deleted_at' => null,
        ]);
    }
}
