<?php

namespace Tests\Feature\App\Http\Controllers\API;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use App\Repositories\CategoryRepository;
use Core\UseCase\Category\ListCategoriesUseCase;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryControllerTest extends TestCase
{
    protected $repository;

    #[Test]
    public function index(): void
    {
        $listCategoriesUseCase = new ListCategoriesUseCase($this->repository);

        $controler = new CategoryController();
        $response = $controler->index(new Request(), $listCategoriesUseCase);

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertArrayHasKey('meta', $response->additional);
    }

    protected function setUp(): void
    {
        $this->repository = new CategoryRepository(new Category());
        parent::setUp();
    }
}
