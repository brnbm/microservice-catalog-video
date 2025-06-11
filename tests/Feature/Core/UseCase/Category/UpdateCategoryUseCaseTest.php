<?php

namespace Tests\Feature\Core\UseCase\Category;

use Tests\TestCase;
use App\Repositories\Eloquent\CategoryRepository;
use App\Models\Category as CategoryModel;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryUpdateInputDTO;

class UpdateCategoryUseCaseTest extends TestCase
{

    public function testUpdate(): void
    {
        $repository = new CategoryRepository(new CategoryModel());
        $useCase = new UpdateCategoryUseCase($repository);

        $categoryDb = CategoryModel::factory()->create();

        $updateInputDto = new CategoryUpdateInputDTO(
            id: $categoryDb->id,
            name: 'Category 1 Updated',
            description: 'Category 1 Description Updated'
        );

        $resposeUseCase = $useCase->execute($updateInputDto);

        $this->assertEquals($updateInputDto->name, $resposeUseCase->name);
    }
}
