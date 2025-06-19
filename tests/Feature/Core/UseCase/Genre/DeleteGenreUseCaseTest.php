<?php

namespace Tests\Feature\Core\UseCase\Genre;

use Tests\TestCase;
use App\Models\Genre as GenreModel;
use Core\UseCase\DTO\Genre\GenreInputDTO;
use Core\UseCase\Genre\DeleteGenreUseCase;
use App\Repositories\Eloquent\GenreRepository;

class DeleteGenreUseCaseTest extends TestCase
{
    public function testDelete()
    {
        $repository = new GenreRepository(new GenreModel());
        $useCase = new DeleteGenreUseCase($repository);

        $genreDb = GenreModel::factory()->create();

        $categoryOutputDto = new GenreInputDTO(
            id: $genreDb->id
        );

        $useCase->execute($categoryOutputDto);

        $this->assertSoftDeleted($genreDb);
    }
}
