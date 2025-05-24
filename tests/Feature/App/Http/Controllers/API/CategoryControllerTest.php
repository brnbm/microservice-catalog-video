<?php

namespace Tests\Feature\App\Http\Controllers\API;

use Tests\TestCase;
use App\Models\Category;
use PHPUnit\Framework\Attributes\Test;
use App\Repositories\CategoryRepository;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\{
    Request,
    JsonResponse
};
use Core\UseCase\Category\{
    CreateCategoryUseCase,
    ListCategoriesUseCase
};
use Symfony\Component\HttpFoundation\{
    ParameterBag,
    Response
};

class CategoryControllerTest extends TestCase
{
    protected $controller;
    protected $repository;

    #[Test]
    public function index(): void
    {
        $listCategoriesUseCase = new ListCategoriesUseCase($this->repository);
        $response = $this->controller->index(new Request(), $listCategoriesUseCase);

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertArrayHasKey('meta', $response->additional);
    }

    #[Test]
    public function store(): void
    {
        $request = new StoreCategoryRequest();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag([
            'name' => 'Test Category',
            'description' => 'Test Description',
        ]));

        $createCategoryUseCase = new CreateCategoryUseCase($this->repository);
        $response = $this->controller->store($request, $createCategoryUseCase);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    protected function setUp(): void
    {
        $this->controller = new CategoryController();
        $this->repository = new CategoryRepository(new Category());
        parent::setUp();
    }
}
