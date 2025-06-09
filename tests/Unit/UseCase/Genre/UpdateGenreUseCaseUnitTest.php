<?php

namespace Tests\Unit\UseCase\Genre;

use Mockery;
use stdClass;
use PHPUnit\Framework\TestCase;
use Core\Domain\ValueObject\Uuid;
use Core\Domain\Entity\GenreEntity;
use PHPUnit\Framework\Attributes\Test;
use Core\UseCase\Genre\UpdateGenreUseCase;
use Core\Domain\Exception\NotFoundDomainException;
use Core\UseCase\Interfaces\TransactionsInterface;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Core\UseCase\DTO\Genre\{GenreUpdateInputDTO, GenreOutputDTO};
use Core\Domain\Repository\{GenreRepositoryInterface, CategoryRepositoryInterface};


class UpdateGenreUseCaseUnitTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private $mockEntity;
    private $mockRepository;
    private $mockTransactions;
    private $mockUpdateInputDto;
    private $mockCategoryRepository;

    #[Test]
    public function updateGenre()
    {
        $useCase = new UpdateGenreUseCase($this->mockRepository(), $this->mockCategoryRepository(), $this->mockTransactions());

        $this->mockUpdateInputDto($this->mockEntity->id, 'Genre Updated');

        $responseUseCase = $useCase->execute($this->mockUpdateInputDto);

        $this->assertInstanceOf(GenreOutputDTO::class, $responseUseCase);
    }

    #[Test]
    public function exceptionUpdateGenre()
    {
        $this->expectException(NotFoundDomainException::class);

        $useCase = new UpdateGenreUseCase($this->mockRepository(0), $this->mockCategoryRepository(), $this->mockTransactions());

        $this->mockUpdateInputDto($this->mockEntity->id, 'Genre Updated', ['UuidCategory-1']);

        $useCase->execute($this->mockUpdateInputDto);
    }

    private function mockRepository(int $timesCalled = 1): GenreRepositoryInterface
    {
        $this->mockEntity();

        $this->mockRepository = Mockery::mock(\stdClass::class, GenreRepositoryInterface::class);
        $this->mockRepository->shouldReceive('findById')->once()->andReturn($this->mockEntity);
        $this->mockRepository->shouldReceive('update')->times($timesCalled)->andReturn($this->mockEntity);
        return $this->mockRepository;
    }

    private function mockEntity(): void
    {
        $id = \Ramsey\Uuid\Uuid::uuid7()->toString();

        $this->mockEntity = Mockery::mock(GenreEntity::class, ['Genre', (new Uuid($id))]);
        $this->mockEntity->shouldReceive('update');
        $this->mockEntity->shouldReceive('addCategory');
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));
        $this->mockEntity->shouldReceive('updatedAt')->andReturn(date('Y-m-d H:i:s'));
    }

    private function mockCategoryRepository(array $categoryIds = []): CategoryRepositoryInterface
    {
        $this->mockCategoryRepository = Mockery::mock(\stdClass::class, CategoryRepositoryInterface::class);
        $this->mockCategoryRepository->shouldReceive('getListIdsByIds')->andReturn($categoryIds);
        return $this->mockCategoryRepository;
    }

    private function mockTransactions(): TransactionsInterface
    {
        $this->mockTransactions = Mockery::mock(\stdClass::class, TransactionsInterface::class);
        $this->mockTransactions->shouldReceive('commit');
        $this->mockTransactions->shouldReceive('rollback');
        return $this->mockTransactions;
    }

    private function mockUpdateInputDto(string $genreId, String $name, array $categoryIds = []): GenreUpdateInputDTO
    {
        $this->mockUpdateInputDto = Mockery::mock(GenreUpdateInputDTO::class, [
            $genreId,
            $name,
            $categoryIds
        ]);

        return $this->mockUpdateInputDto;
    }
}
