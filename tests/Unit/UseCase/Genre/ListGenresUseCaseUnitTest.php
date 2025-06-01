<?php

namespace Tests\Unit\UseCase\Genre;

use Mockery;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Core\UseCase\Genre\ListGenresUseCase;
use Core\Domain\Repository\GenreRepositoryInterface;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Core\UseCase\DTO\Genre\{ListGenresInputDTO, ListGenresOutputDTO};

class ListGenresUseCaseUnitTest extends TestCase
{
    /**
     * Recomendável quando há mais de um teste utilizando Mockery
     */
    use MockeryPHPUnitIntegration;

    private $mockRepository;
    private $mockPagination;
    private $genresInputDto;

    #[Test]
    public function listGenresEmpty()
    {
        $this->mockPagination = $this->generateMockPagination();

        $this->mockRepository = Mockery::mock(\stdClass::class, GenreRepositoryInterface::class);
        $this->mockRepository->shouldReceive('paginate')->andReturn($this->mockPagination);

        $this->genresInputDto = Mockery::mock(ListGenresInputDTO::class, []);

        $useCase = new ListGenresUseCase($this->mockRepository);
        $responseUseCase = $useCase->execute($this->genresInputDto);

        $this->assertInstanceOf(ListGenresOutputDTO::class, $responseUseCase);
        $this->assertCount(0, $responseUseCase->items);
    }

    #[Test]
    public function listGenres()
    {
        $object = new \stdClass();
        $object->id = '1';
        $object->name = 'Genre 1';
        $object->description = 'Description 1';
        $object->is_active = true;
        $object->created_at = '2021-01-01 00:00:00';
        $object->updated_at = '2021-01-02 00:00:00';

        $this->mockPagination = $this->generateMockPagination([$object]);

        $this->mockRepository = Mockery::mock(\stdClass::class, GenreRepositoryInterface::class);
        $this->mockRepository->shouldReceive('paginate')->andReturn($this->mockPagination);

        $this->genresInputDto = Mockery::mock(ListGenresInputDTO::class, []);

        $useCase = new ListGenresUseCase($this->mockRepository);
        $responseUseCase = $useCase->execute($this->genresInputDto);

        $this->assertInstanceOf(ListGenresOutputDTO::class, $responseUseCase);
        $this->assertInstanceOf(\stdClass::class, $responseUseCase->items[0]);
        $this->assertCount(1, $responseUseCase->items);
    }
}
