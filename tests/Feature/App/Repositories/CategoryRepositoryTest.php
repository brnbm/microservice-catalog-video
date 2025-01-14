<?php

namespace Tests\Feature\App\Repositories;

use Tests\TestCase;
use App\Repositories\CategoryRepository;
use App\Models\Category as CategoryModel;
use Core\Domain\Entity\Category as CategoryEntity;
use Core\Domain\Exception\NotFoundDomainException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Throwable;

class CategoryRepositoryTest extends TestCase
{
    private $model;
    private $repository;

    protected function setUp(): void
    {
        $this->model = new CategoryModel();
        $this->repository = new CategoryRepository($this->model);

        parent::setUp();
    }

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
        $category = CategoryModel::factory()->create();

        $response = $this->repository->findById($category->id);

        $this->assertInstanceOf(CategoryRepositoryInterface::class, $this->repository);
        $this->assertInstanceOf(CategoryEntity::class, $response);
        $this->assertEquals($category->id, $response->id);
    }

    public function testFindByIdNotFound()
    {
        try {
            $this->repository->findById('fakeValue');
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundDomainException::class, $th);
        }
    }
}
