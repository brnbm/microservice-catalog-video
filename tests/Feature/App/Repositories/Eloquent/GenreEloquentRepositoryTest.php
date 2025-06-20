<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use Tests\TestCase;
use App\Models\Genre as GenreModel;
use Core\Domain\Entity\GenreEntity;
use App\Models\Category as CategoryModel;
use App\Repositories\Eloquent\GenreRepository;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Exception\NotFoundDomainException;
use Core\Domain\Repository\GenreRepositoryInterface;


class GenreEloquentRepositoryTest extends TestCase
{
    private $model;
    private $repository;

    public function testInsert()
    {
        $name = 'Genre A';
        $entity = new GenreEntity(name: $name);
        $entity->deactivate();

        $response = $this->repository->insert($entity);

        $this->assertInstanceOf(GenreRepositoryInterface::class, $this->repository);
        $this->assertInstanceOf(GenreEntity::class, $response);
        $this->assertDatabaseHas($this->model->getTable(), ['name' => $entity->name, 'is_active' => false]);
    }

    public function testInsertWithRelationships()
    {
        // * Gera 2 registros fake no banco de dados testing
        $categoriesFake = CategoryModel::factory()->count(2)->create();

        // * Pega os IDs dos registros criados
        $categoriesIds = $categoriesFake->pluck('id')->toArray();

        $name = 'Genre A';
        $entity = new GenreEntity(name: $name, categoriesId: $categoriesIds);

        $response = $this->repository->insert($entity);

        $this->assertInstanceOf(GenreRepositoryInterface::class, $this->repository);
        $this->assertInstanceOf(GenreEntity::class, $response);
        $this->assertDatabaseHas($this->model->getTable(), ['name' => $entity->name]);
        $this->assertDatabaseCount('category_genre', 2);
    }

    public function testFindById()
    {
        /**
         * * Gera 1 registro fake no banco de dados testing
         */
        $genre = GenreModel::factory()->create();

        $response = $this->repository->findById($genre->id);

        $this->assertInstanceOf(GenreRepositoryInterface::class, $this->repository);
        $this->assertInstanceOf(GenreEntity::class, $response);
        $this->assertEquals($genre->id, $response->id);
    }

    public function testFindByIdNotFound()
    {
        try {
            $this->repository->findById('fakeID');
        } catch (\Throwable $th) {
            $this->assertInstanceOf(NotFoundDomainException::class, $th);
        }
    }

    public function testFindAll()
    {
        /**
         * * Gera 10 registros fake no banco de dados testing
         */
        GenreModel::factory()->count(10)->create();

        $response = $this->repository->findAll();
        $this->assertCount(10, $response);
    }

    public function testFindAllWithFilter()
    {
        /**
         * * Gera 10 registros fake no banco de dados testing
         */
        GenreModel::factory()->count(10)->create();
        /**
         * * Gera 1 registro fake no banco de dados testing
         */
        GenreModel::factory()->create(['name' => 'Genre A']);

        $response = $this->repository->findAll();
        $this->assertCount(11, $response);

        $response = $this->repository->findAll(filter: 'Genre A');
        $this->assertCount(1, $response);
    }

    public function testPaginate()
    {
        GenreModel::factory()->count(20)->create();

        $perPage = 10;
        $response = $this->repository->paginate(totalPage: $perPage);

        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertEquals($perPage, $response->perPage());
        $this->assertIsArray($response->items());
    }

    public function testPaginateEmpty()
    {
        $response = $this->repository->paginate();

        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertEquals(0, $response->total());
    }

    public function testUpdate()
    {
        $genre = GenreModel::factory()->create();
        $responsefindById = $this->repository->findById($genre->id);

        $responsefindById->update(name: 'Genre Updated');
        $responsefindById->deactivate();

        $responseUpdate = $this->repository->update($responsefindById);

        $this->assertInstanceOf(GenreEntity::class, $responseUpdate);
        $this->assertEquals('Genre Updated', $responseUpdate->name);
        $this->assertEquals(false, $responseUpdate->isActive);
    }

    public function testUpdateException()
    {
        $this->expectException(NotFoundDomainException::class);

        $genreEntity = new GenreEntity(name: 'Category A');

        $this->repository->update($genreEntity);
    }

    public function testDelete()
    {
        $genre = GenreModel::factory()->create();

        $this->repository->delete($genre->id);

        $this->assertSoftDeleted($this->model->getTable(), ['id' => $genre->id]);
    }

    public function testDeleteException()
    {
        $this->expectException(NotFoundDomainException::class);

        $this->repository->delete('fakeID');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = new GenreModel();
        $this->repository = new GenreRepository($this->model);
    }
}
