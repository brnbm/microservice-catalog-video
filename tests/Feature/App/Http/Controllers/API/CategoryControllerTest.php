<?php

namespace Tests\Feature\App\Http\Controllers\API;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Repositories\CategoryRepository;
use App\Models\Category as CategoryModel;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\{
    Request,
    JsonResponse
};
use App\Http\Requests\{
    DeleteCategoryRequest,
    StoreCategoryRequest,
    UpdateCategoryRequest
};
use Core\UseCase\Category\{
    CreateCategoryUseCase,
    DeleteCategoryUseCase,
    ListCategoriesUseCase,
    ListCategoryUseCase,
    UpdateCategoryUseCase
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

    #[Test]
    public function show(): void
    {
        /**
         * * Cria um registro fictício no banco de dados para teste.
         */
        $category = CategoryModel::factory()->create();

        $response = $this->controller->show(
            id: $category->id,
            useCase: new ListCategoryUseCase(
                $this->repository
            )
        );

        $responseData = $response->getData();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($category->id, $responseData->data->id);
    }

    #[Test]
    public function update(): void
    {
        $category = CategoryModel::factory()->create();

        $request = new UpdateCategoryRequest();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag([
            'name' => 'Updated name Category',
            'description' => 'Updated Description Category',
            'is_active' => false,
        ]));

        $updateCategoryUseCase = new UpdateCategoryUseCase($this->repository);
        $response = $this->controller->update($category->id, $request, $updateCategoryUseCase);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertDatabaseHas('categories', [
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->is_active,
        ]);
    }

    #[Test]
    public function destroy(): void
    {
        $category = CategoryModel::factory()->create();

        $deleteCategoryUseCase = new DeleteCategoryUseCase($this->repository);
        $response = $this->controller->destroy($category->id, $deleteCategoryUseCase);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertSoftDeleted($category);
    }

    protected function setUp(): void
    {
        $this->controller = new CategoryController();
        $this->repository = new CategoryRepository(new CategoryModel());
        parent::setUp();
    }
}
