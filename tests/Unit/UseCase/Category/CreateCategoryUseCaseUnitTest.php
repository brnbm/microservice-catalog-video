<?php

namespace Tests\Unit\UseCase\Category;

use Mockery;
use stdClass;
use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Category;
use Core\Domain\ValueObject\Uuid;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryCreateInputDTO;
use Core\UseCase\DTO\Category\CategoryOutputDTO;
use Core\Domain\Repository\CategoryRepositoryInterface;

class CreateCategoryUseCaseUnitTest extends TestCase
{
    private $mockEntity;
    private $mockRepository;
    private $categoryCreateInputDto;

    public function testCreateNewCategory()
    {
        $id = Uuid::random();
        $name = 'testCreateNewCategory';

        $this->mockEntity = Mockery::mock(Category::class, [$id, $name]);
        $this->mockEntity->shouldReceive('id')->andReturn($id);
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $this->mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepository->shouldReceive('insert')->times(1)->andReturn($this->mockEntity);

        $this->categoryCreateInputDto = Mockery::mock(CategoryCreateInputDTO::class, [$name]);

        $useCase = new CreateCategoryUseCase($this->mockRepository);
        $responseUseCase = $useCase->execute($this->categoryCreateInputDto);

        $this->assertInstanceOf(CategoryOutputDTO::class, $responseUseCase);
        $this->assertEquals($name, $responseUseCase->name);
    }
}
