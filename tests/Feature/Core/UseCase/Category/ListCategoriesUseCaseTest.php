<?php

namespace Tests\Feature\Core\UseCase\Category;

use Tests\TestCase;
use App\Repositories\Eloquent\CategoryRepository;
use App\Models\Category as CategoryModel;
use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\DTO\Category\ListCategoriesInputDTO;

class ListCategoriesUseCaseTest extends TestCase
{
    private $useCase;

    public function testListEmpty(): void
    {
        $responseUseCase = $this->useCase->execute(new ListCategoriesInputDTO());
        $this->assertCount(0, $responseUseCase->items);
    }

    public function testListAll(): void
    {
        CategoryModel::factory()->count(20)->create();

        $responseUseCase = $this->useCase->execute(new ListCategoriesInputDTO());
        $this->assertEquals(20, $responseUseCase->total);
    }

    protected function setUp(): void
    {
        $repository = new CategoryRepository(new CategoryModel());
        $this->useCase = new ListCategoriesUseCase($repository);

        parent::setUp();
    }
}
