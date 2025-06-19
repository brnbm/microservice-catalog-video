<?php

namespace Tests\Feature\Core\UseCase\Genre;

use Tests\TestCase;
use App\Models\Genre as GenreModel;
use Core\UseCase\Genre\ListGenresUseCase;
use Core\UseCase\DTO\Genre\ListGenresInputDTO;
use App\Repositories\Eloquent\GenreRepository;

class ListGenresUseCaseTest extends TestCase
{
    private $useCase;

    public function testListEmpty(): void
    {
        $responseUseCase = $this->useCase->execute(new ListGenresInputDTO());
        $this->assertCount(0, $responseUseCase->items);
    }

    public function testListAll(): void
    {
        GenreModel::factory()->count(20)->create();

        $responseUseCase = $this->useCase->execute(new ListGenresInputDTO(totalPage: 5));
        $this->assertCount(5, $responseUseCase->items);
        $this->assertEquals(20, $responseUseCase->total);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $repository = new GenreRepository(new GenreModel());
        $this->useCase = new ListGenresUseCase($repository);
    }
}
