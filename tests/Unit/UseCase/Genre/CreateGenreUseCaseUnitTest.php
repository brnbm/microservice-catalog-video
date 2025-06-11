<?php

namespace Tests\Unit\UseCase\Genre;

use Mockery;
use stdClass;
use PHPUnit\Framework\TestCase;
use Core\Domain\ValueObject\Uuid;
use Core\Domain\Entity\GenreEntity;
use PHPUnit\Framework\Attributes\Test;
use Core\UseCase\Genre\CreateGenreUseCase;
use Core\Domain\Exception\NotFoundDomainException;
use Core\UseCase\Interfaces\TransactionInterface;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Core\UseCase\DTO\Genre\{GenreCreateInputDTO, GenreOutputDTO};
use Core\Domain\Repository\{GenreRepositoryInterface, CategoryRepositoryInterface};


class CreateGenreUseCaseUnitTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private $mockEntity;
    private $mockRepository;
    private $mockTransactions;
    private $genrereateInputDto;
    private $mockCategoryRepository;

    #[Test]
    public function createNewGenre()
    {
        $name = 'testCreateNewGenre';
        $this->mockEntity($name);

        $useCase = new CreateGenreUseCase(
            $this->mockRepository(),
            $this->mockCategoryRepository(),
            $this->mockTransactions()
        );

        $this->genrereateInputDto($name);
        $responseUseCase = $useCase->execute($this->genrereateInputDto);

        $this->assertInstanceOf(GenreOutputDTO::class, $responseUseCase);
        $this->assertEquals($name, $responseUseCase->name);
    }

    #[Test]
    public function exceptionCreateNewGenre()
    {
        $this->expectException(NotFoundDomainException::class);

        $name = 'testExceptionCreateNewGenre';
        $this->mockEntity($name);

        $useCase = new CreateGenreUseCase(
            $this->mockRepository(0),
            $this->mockCategoryRepository(['UuidCategory-1']),
            $this->mockTransactions()
        );

        $this->genrereateInputDto($name, ['UuidCategory-1', 'UuidCategory-2']);
        $responseUseCase = $useCase->execute($this->genrereateInputDto);

        $this->assertInstanceOf(GenreOutputDTO::class, $responseUseCase);
        $this->assertEquals($name, $responseUseCase->name);
    }

    private function mockEntity(String $name): void
    {
        $id = \Ramsey\Uuid\Uuid::uuid7()->toString();

        $this->mockEntity = Mockery::mock(GenreEntity::class, [$name, (new Uuid($id))]);
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));
    }

    private function mockRepository(int $timesCalled = 1): GenreRepositoryInterface
    {
        $this->mockRepository = Mockery::mock(\stdClass::class, GenreRepositoryInterface::class);
        $this->mockRepository->shouldReceive('insert')->times($timesCalled)->andReturn($this->mockEntity);
        return $this->mockRepository;
    }

    private function mockCategoryRepository(array $listIds = []): CategoryRepositoryInterface
    {
        $this->mockCategoryRepository = Mockery::mock(\stdClass::class, CategoryRepositoryInterface::class);
        $this->mockCategoryRepository->shouldReceive('getListIdsByIds')->once()->andReturn($listIds);
        return $this->mockCategoryRepository;
    }

    private function mockTransactions(): TransactionInterface
    {
        $this->mockTransactions = Mockery::mock(\stdClass::class, TransactionInterface::class);
        $this->mockTransactions->shouldReceive('commit');
        $this->mockTransactions->shouldReceive('rollback');
        return $this->mockTransactions;
    }

    private function genrereateInputDto(String $name, array $listIds = []): GenreCreateInputDTO
    {
        $this->genrereateInputDto = Mockery::mock(GenreCreateInputDTO::class, [$name, $listIds]);
        return $this->genrereateInputDto;
    }
}
