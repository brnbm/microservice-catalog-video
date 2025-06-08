<?php

namespace Tests\Unit\UseCase\Genre;

use Mockery;
use PHPUnit\Framework\TestCase;
use Core\Domain\ValueObject\Uuid;
use Core\Domain\Entity\GenreEntity;
use Core\UseCase\DTO\Genre\GenreInputDTO;
use Core\UseCase\Genre\DeleteGenreUseCase;
use Core\Domain\Repository\GenreRepositoryInterface;

class DeleteGenreUseCaseUnitTest extends TestCase
{
    private $mockEntity;
    private $mockInputDto;
    private $mockRepository;

    public function testDeleteGenre()
    {
        $id = \Ramsey\Uuid\Uuid::uuid7()->toString();
        $name = 'Genre Test';

        $this->mockEntity = Mockery::mock(GenreEntity::class, [$name, (new Uuid($id))]);
        $this->mockEntity->shouldReceive('id')->andReturn($id);

        $this->mockRepository = Mockery::mock(\stdClass::class, GenreRepositoryInterface::class);
        $this->mockRepository->shouldReceive('findById')->times(1)->andReturn($this->mockEntity);
        $this->mockRepository->shouldReceive('delete')->andReturnTrue();

        $this->mockInputDto = Mockery::mock(GenreInputDTO::class, [$id]);

        $useCase = new DeleteGenreUseCase($this->mockRepository);
        $responseUseCase = $useCase->execute($this->mockInputDto);

        $this->assertTrue($responseUseCase);
    }
}
