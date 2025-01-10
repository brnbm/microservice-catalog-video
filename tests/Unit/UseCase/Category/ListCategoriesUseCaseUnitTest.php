<?php

namespace Tests\Unit\UseCase\Category;

use Mockery;
use stdClass;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Core\Domain\Repository\PaginationInterface;
use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\DTO\Category\ListCategoriesInputDTO;
use Core\UseCase\DTO\Category\ListCategoriesOutputDTO;
use Core\Domain\Repository\CategoryRepositoryInterface;

class ListCategoriesUseCaseUnitTest extends TestCase
{
    private $mockRepository;
    private $mockPagination;
    private $categoriesInputDto;

    public function testListCategoriesEmpty()
    {
        $this->mockPagination = $this->generateMockPagination();

        $this->mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepository->shouldReceive('paginate')->andReturn($this->mockPagination);

        $this->categoriesInputDto = Mockery::mock(ListCategoriesInputDTO::class, []);

        $useCase = new ListCategoriesUseCase($this->mockRepository);
        $responseUseCase = $useCase->execute($this->categoriesInputDto);

        $this->assertInstanceOf(ListCategoriesOutputDTO::class, $responseUseCase);
        $this->assertCount(0, $responseUseCase->items);
    }

    #[Test]
    public function listCategories()
    {
        $object = new stdClass();
        $object->id = '1';
        $object->name = 'Category 1';
        $object->description = 'Description 1';
        $object->is_active = true;
        $object->created_at = '2021-01-01 00:00:00';
        $object->updated_at = '2021-01-02 00:00:00';

        $this->mockPagination = $this->generateMockPagination([$object]);

        $this->mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepository->shouldReceive('paginate')->andReturn($this->mockPagination);

        $this->categoriesInputDto = Mockery::mock(ListCategoriesInputDTO::class, []);

        $useCase = new ListCategoriesUseCase($this->mockRepository);
        $responseUseCase = $useCase->execute($this->categoriesInputDto);

        $this->assertInstanceOf(ListCategoriesOutputDTO::class, $responseUseCase);
        $this->assertInstanceOf(stdClass::class, $responseUseCase->items[0]);
        $this->assertCount(1, $responseUseCase->items);
    }

    protected function generateMockPagination(array $items = [])
    {
        $mockPagination = Mockery::mock(stdClass::class, PaginationInterface::class);
        $mockPagination->shouldReceive('items')->andReturn($items);
        $mockPagination->shouldReceive('total')->andReturn(0);
        $mockPagination->shouldReceive('lastPage')->andReturn(0);
        $mockPagination->shouldReceive('firstPage')->andReturn(0);
        $mockPagination->shouldReceive('currentPage')->andReturn(0);
        $mockPagination->shouldReceive('perPage')->andReturn(0);
        $mockPagination->shouldReceive('to')->andReturn(0);
        $mockPagination->shouldReceive('from')->andReturn(0);

        return $mockPagination;
    }

    /**
     * Necessário quando há mais de um teste utilizando Mockery
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
