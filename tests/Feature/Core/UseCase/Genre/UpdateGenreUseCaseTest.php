<?php

namespace Tests\Feature\Core\UseCase\Genre;

use Tests\TestCase;
use App\Models\Genre as GenreModel;
use App\Models\Category as CategoryModel;
use Core\UseCase\Genre\UpdateGenreUseCase;
use App\Repositories\Transaction\DBTransaction;
use Core\UseCase\DTO\Genre\GenreUpdateInputDTO;
use App\Repositories\Eloquent\{GenreRepository, CategoryRepository};

class UpdateGenreUseCaseTest extends TestCase
{

    public function testUpdate(): void
    {
        $genreRepository = new GenreRepository(new GenreModel());
        $categoryRepository = new CategoryRepository(new CategoryModel());

        $useCase = new UpdateGenreUseCase(
            $genreRepository,
            $categoryRepository,
            new DBTransaction()
        );

        $genreDb = GenreModel::factory()->create();
        $categoriesId = CategoryModel::factory()->count(3)->create()->pluck('id')->toArray();

        $updateInputDto = new GenreUpdateInputDTO(
            id: $genreDb->id,
            name: 'Genre 1 Updated',
            categoriesId: $categoriesId,
            is_active: false
        );

        $resposeUseCase = $useCase->execute($updateInputDto);

        $this->assertEquals($updateInputDto->name, $resposeUseCase->name);
        $this->assertDatabaseCount('category_genre', 3);
    }
}
