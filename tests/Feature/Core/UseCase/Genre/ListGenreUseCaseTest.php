<?php

namespace Tests\Feature\Core\UseCase\Genre;

use Tests\TestCase;
use App\Models\Genre as GenreModel;
use Core\UseCase\Genre\ListGenreUseCase;
use Core\UseCase\DTO\Genre\GenreInputDTO;
use App\Repositories\Eloquent\GenreRepository;
use Core\Domain\Exception\NotFoundDomainException;

class ListGenreUseCaseTest extends TestCase
{
    public function testList(): void
    {
        $repository = new GenreRepository(new GenreModel());
        $useCase = new ListGenreUseCase($repository);

        $categoryDb = GenreModel::factory()->create([
            'name' => 'Genre Test',
        ]);

        $responseUseCase = $useCase->execute(new GenreInputDTO(id: $categoryDb->id));

        $this->assertEquals($categoryDb->id, $responseUseCase->id);
        $this->assertEquals('Genre Test', $responseUseCase->name);
    }

    public function testListException(): void
    {
        $this->expectException(NotFoundDomainException::class);

        $repository = new GenreRepository(new GenreModel());
        $useCase = new ListGenreUseCase($repository);

        $useCase->execute(new GenreInputDTO(id: 'fakeId'));
    }
}
