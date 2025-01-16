<?php

namespace Tests\Feature\Core\UseCase\Category;

use Tests\TestCase;
use App\Repositories\CategoryRepository;
use App\Models\Category as CategoryModel;
use Core\UseCase\DTO\Category\CategoryInputDTO;
use Core\UseCase\Category\DeleteCategoryUseCase;

class DeleteCategoryUseCaseTest extends TestCase
{
    public function testDelete()
    {
        $repository = new CategoryRepository(new CategoryModel());
        $useCase = new DeleteCategoryUseCase($repository);

        $categoryFactory = CategoryModel::factory()->create();

        $categoryOutputDto = new CategoryInputDTO(
            id: $categoryFactory->id
        );

        $useCase->execute($categoryOutputDto);

        $this->assertSoftDeleted($categoryFactory);
    }
}
