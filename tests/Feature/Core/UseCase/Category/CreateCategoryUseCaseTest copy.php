<?php

namespace Tests\Feature\Core\UseCase\Category;

use Tests\TestCase;
use App\Repositories\CategoryRepository;
use App\Models\Category as CategoryModel;
use Core\UseCase\DTO\Category\CategoryOutputDTO;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryCreateInputDTO;

class CreateCategoryUseCaseTest extends TestCase
{
    public function testCreate()
    {
        $repository = new CategoryRepository(new CategoryModel());
        $useCase = new CreateCategoryUseCase($repository);

        $categoryInputDto = new CategoryCreateInputDTO(
            name: 'Category Test',
            description: 'Category Test Description'
        );

        $responseUseCase = $useCase->execute($categoryInputDto);

        $this->assertNotEmpty($responseUseCase->id);
        $this->assertEquals($categoryInputDto->name, $responseUseCase->name);
    }
}
