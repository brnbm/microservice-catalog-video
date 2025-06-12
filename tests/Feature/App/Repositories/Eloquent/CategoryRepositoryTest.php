<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use Throwable;
use Tests\TestCase;
use App\Models\Category as CategoryModel;
use Core\Domain\Repository\PaginationInterface;
use App\Repositories\Eloquent\CategoryRepository;
use Core\Domain\Entity\Category as CategoryEntity;
use Core\Domain\Exception\NotFoundDomainException;
use Core\Domain\Repository\CategoryRepositoryInterface;

class CategoryRepositoryTest extends TestCase
{
    private $model;
    private $repository;

    public function testInsert()
    {
        $name = 'Categoria A';
        $entity = new CategoryEntity(name: $name);

        $response = $this->repository->insert($entity);

        $this->assertInstanceOf(CategoryRepositoryInterface::class, $this->repository);
        $this->assertInstanceOf(CategoryEntity::class, $response);
        $this->assertDatabaseHas($this->model->getTable(), ['name' => $entity->name]);
    }

    public function testFindById()
    {
        /**
         * * Gera 1 registro fake no banco de dados testing
         */
        $category = CategoryModel::factory()->create();

        $response = $this->repository->findById($category->id);

        $this->assertInstanceOf(CategoryRepositoryInterface::class, $this->repository);
        $this->assertInstanceOf(CategoryEntity::class, $response);
        $this->assertEquals($category->id, $response->id);
    }

    public function testFindByIdNotFound()
    {
        try {
            $this->repository->findById('fakeID');
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundDomainException::class, $th);
        }
    }

    public function testFindAll()
    {
        /**
         * * Gera 10 registros fake no banco de dados testing
         */
        CategoryModel::factory()->count(10)->create();

        $response = $this->repository->findAll();
        $this->assertCount(10, $response);
    }

    public function testPaginate()
    {
        CategoryModel::factory()->count(20)->create();

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
        $category = CategoryModel::factory()->create();
        $responsefindById = $this->repository->findById($category->id);

        $responsefindById->update(name: 'Category Updated', isActive: false);

        $responseUpdate = $this->repository->update($responsefindById);

        $this->assertInstanceOf(CategoryEntity::class, $responseUpdate);
        $this->assertEquals('Category Updated', $responseUpdate->name);
        $this->assertEquals(false, $responseUpdate->isActive);
    }

    public function testUpdateException()
    {
        $categoryEntity = new CategoryEntity(
            name: 'Category A',
            description: 'Description Category A'
        );

        try {
            $this->repository->update($categoryEntity);
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundDomainException::class, $th);
        }
    }

    public function testDelete()
    {
        $category = CategoryModel::factory()->create();

        $isDeleted = $this->repository->delete($category->id);

        $this->assertTrue($isDeleted);
    }

    public function testDeleteException()
    {
        try {
            $this->repository->delete('fakeID');
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundDomainException::class, $th);
        }
    }

    protected function setUp(): void
    {
        $this->model = new CategoryModel();
        $this->repository = new CategoryRepository($this->model);

        parent::setUp();
    }
}
