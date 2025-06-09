<?php

namespace Tests\Unit\UseCase\Genre;

use Mockery;
use Tests\TestCase;
use Core\Domain\ValueObject\Uuid;
use Core\Domain\Entity\GenreEntity;
use PHPUnit\Framework\Attributes\Test;
use Core\UseCase\Genre\ListGenreUseCase;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\{GenreInputDTO, GenreOutputDTO};

class ListGenreUseCaseUnitTest extends TestCase
{
    private $mockEntity;
    private $mockRepository;
    private $genreInputDto;

    #[Test]
    public function getById()
    {
        $uuid = \Ramsey\Uuid\Uuid::uuid7()->toString();

        $this->mockEntity = Mockery::mock(GenreEntity::class, ['Test Genre', (new Uuid($uuid))]);
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));
        $this->mockEntity->shouldReceive('updatedAt')->andReturn(date('Y-m-d H:i:s'));

        $this->mockRepository = Mockery::mock(\stdClass::class, GenreRepositoryInterface::class);
        $this->mockRepository->shouldReceive('findById')->once()->with($uuid)->andReturn($this->mockEntity);

        $this->genreInputDto = Mockery::mock(GenreInputDTO::class, [$uuid]);

        $useCase = new ListGenreUseCase($this->mockRepository);
        $responseUseCase = $useCase->execute($this->genreInputDto);

        $this->assertInstanceOf(GenreOutputDTO::class, $responseUseCase);
        $this->assertEquals($uuid, $responseUseCase->id);
        $this->assertEquals('Test Genre', $responseUseCase->name);
    }
}
