<?php

namespace Tests\Unit\UseCase\Category;

use Mockery;
use stdClass;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Category;
use Core\UseCase\DTO\Category\CategoryOutputDTO;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryUpdateInputDTO;
use Core\Domain\Repository\CategoryRepositoryInterface;

class UpdateCategoryUseCaseUnitTest extends TestCase
{
    private $mockEntity;
    private $mockInputDto;
    private $mockRepository;

    public function testUpdateCategory()
    {
        $id = Uuid::uuid4()->toString();
        $name = 'nameCategory';
        $description = 'descriptionCategory';

        $this->mockEntity = Mockery::mock(Category::class, [$id, $name, $description]);
        $this->mockEntity->shouldReceive('update');
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));
        $this->mockEntity->shouldReceive('updatedAt')->andReturn(date('Y-m-d H:i:s'));


        $this->mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepository->shouldReceive('findById')->andReturn($this->mockEntity);
        $this->mockRepository->shouldReceive('update')->andReturn($this->mockEntity);

        $this->mockInputDto = Mockery::mock(CategoryUpdateInputDTO::class, [$id, 'updatedNameCategory', 'updatedDescriptionCategory']);

        $useCase = new UpdateCategoryUseCase($this->mockRepository);
        $responseUseCase = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CategoryOutputDTO::class, $responseUseCase);
    }
}
