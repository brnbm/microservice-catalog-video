<?php

namespace Tests\Unit\UseCase\Category;

use Mockery;
use stdClass;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;
use Core\UseCase\DTO\Category\CategoryInputDTO;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Core\Domain\Entity\Category as CategoryEntity;
use Core\Domain\Repository\CategoryRepositoryInterface;

class DeleteCategoryUseCaseUnitTest extends TestCase
{
    private $mockEntity;
    private $mockInputDto;
    private $mockRepository;

    public function testDeleteCategory()
    {
        $id = Uuid::uuid4()->toString();
        $name = 'Category Test';

        $this->mockEntity = Mockery::mock(CategoryEntity::class, [$id, $name]);
        $this->mockEntity->shouldReceive('id')->andReturn($id);

        $this->mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepository->shouldReceive('findById')->times(1)->andReturn($this->mockEntity);
        $this->mockRepository->shouldReceive('delete')->andReturnTrue();

        $this->mockInputDto = Mockery::mock(CategoryInputDTO::class, [$id]);

        $useCase = new DeleteCategoryUseCase($this->mockRepository);
        $responseUseCase = $useCase->execute($this->mockInputDto);

        $this->assertTrue($responseUseCase);
    }
}
