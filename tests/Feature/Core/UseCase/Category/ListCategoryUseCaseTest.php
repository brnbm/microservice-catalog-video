<?php

namespace Tests\Feature\Core\UseCase\Category;

use Tests\TestCase;

use App\Repositories\CategoryRepository;
use App\Models\Category as CategoryModel;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryInputDTO;

class ListCategoryUseCaseTest extends TestCase
{
    public function testList(): void
    {
        $repository = new CategoryRepository(new CategoryModel());
        $useCase = new ListCategoryUseCase($repository);

        $categoryDb = CategoryModel::factory()->create([
            'name' => 'Category XPTO',
            'description' => 'Description XPTO'
        ]);

        $responseUseCase = $useCase->execute(
            new CategoryInputDTO(
                id: $categoryDb->id
            )
        );

        $this->assertEquals($categoryDb->description, $responseUseCase->description);
    }
}
