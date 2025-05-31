<?php

namespace Tests\Unit\UseCase\Category;

use Mockery;
use stdClass;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Category;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryInputDTO;
use Core\UseCase\DTO\Category\CategoryOutputDTO;
use Core\Domain\Repository\CategoryRepositoryInterface;

class ListCategoryUseCaseUnitTest extends TestCase
{
    private $mockEntity;
    private $mockRepository;
    private $categoryInputDto;

    public function testGetById()
    {
        $uuid = (string) Uuid::uuid4()->toString();

        $this->mockEntity = Mockery::mock(Category::class, [$uuid, 'Test Category']);
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));
        $this->mockEntity->shouldReceive('updatedAt')->andReturn(date('Y-m-d H:i:s'));

        $this->mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepository->shouldReceive('findById')->with($uuid)->andReturn($this->mockEntity);

        $this->categoryInputDto = Mockery::mock(CategoryInputDTO::class, [$uuid]);

        $useCase = new ListCategoryUseCase($this->mockRepository);
        $responseUseCase = $useCase->execute($this->categoryInputDto);

        $this->assertInstanceOf(CategoryOutputDTO::class, $responseUseCase);
        $this->assertEquals($uuid, $responseUseCase->id);
        $this->assertEquals('Test Category', $responseUseCase->name);
    }
}
