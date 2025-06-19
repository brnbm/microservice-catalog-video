<?php

namespace Tests\Feature\Core\UseCase\Genre;

use Tests\TestCase;
use App\Models\Genre as GenreModel;
use App\Models\Category as CategoryModel;
use Core\UseCase\Genre\CreateGenreUseCase;
use App\Repositories\Transaction\DBTransaction;
use Core\UseCase\DTO\Genre\GenreCreateInputDTO;
use Core\Domain\Exception\NotFoundDomainException;
use App\Repositories\Eloquent\{CategoryRepository, GenreRepository};

class CreateGenreUseCaseTest extends TestCase
{
    private $genreRepository;
    private $categoryRepository;

    public function testCreate()
    {
        $useCase = new CreateGenreUseCase(
            $this->genreRepository,
            $this->categoryRepository,
            new DBTransaction()
        );

        $categoryInputDto = new GenreCreateInputDTO(
            name: 'Genre Test',
        );

        $responseUseCase = $useCase->execute($categoryInputDto);

        $this->assertNotEmpty($responseUseCase->id);
        $this->assertEquals($categoryInputDto->name, $responseUseCase->name);
    }

    public function testCreateWithCategories()
    {
        $useCase = new CreateGenreUseCase(
            $this->genreRepository,
            $this->categoryRepository,
            new DBTransaction()
        );

        $categoriesId = CategoryModel::factory()->count(3)->create()->pluck('id')->toArray();

        $categoryInputDto = new GenreCreateInputDTO(
            name: 'Genre Test with relationships',
            categoriesId: $categoriesId
        );

        $responseUseCase = $useCase->execute($categoryInputDto);

        $this->assertNotEmpty($responseUseCase->id);
        $this->assertEquals($categoryInputDto->name, $responseUseCase->name);
        $this->assertDatabaseCount('category_genre', 3);
    }

    public function testCreateException()
    {
        $this->expectException(NotFoundDomainException::class);

        $useCase = new CreateGenreUseCase(
            $this->genreRepository,
            $this->categoryRepository,
            new DBTransaction()
        );

        $categoriesId = ['uuidFake-1', 'uuidFake-2'];

        $categoryInputDto = new GenreCreateInputDTO(
            name: 'Genre Test with exception',
            categoriesId: $categoriesId
        );

        $useCase->execute($categoryInputDto);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->genreRepository = new GenreRepository(new GenreModel());
        $this->categoryRepository = new CategoryRepository(new CategoryModel());
    }
}
