<?php

namespace App\Repositories;

use App\Presenters\PaginationPresenter;
use App\Models\Category as CategoryModel;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Entity\Category as CategoryEntity;
use Core\Domain\Exception\NotFoundDomainException;
use Core\Domain\Repository\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    private CategoryModel $model;

    public function __construct(CategoryModel $model)
    {
        $this->model = $model;
    }

    public function insert(CategoryEntity $entity): CategoryEntity
    {
        $result = $this->model->create([
            'id' => $entity->id(),
            'name' => $entity->name
        ]);

        return $this->toCategoryEntity($result);
    }

    public function findById(string $id): CategoryEntity
    {
        $result = $this->model->find($id);

        if (is_null($result)) {
            throw new NotFoundDomainException('Category not found.');
        }

        return $this->toCategoryEntity($result);
    }

    public function findAll(string $filter = '', string $order = 'DESC'): array
    {
        $results = $this->model->all();
        $categories = [];

        foreach ($results as $result) {
            $categories[] = $this->toCategoryEntity($result);
        }

        return $categories;
    }

    public function paginate(string $filter = '', string $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        return new PaginationPresenter(null);
    }

    public function update(CategoryEntity $entity): CategoryEntity
    {
        return new CategoryEntity();
    }

    public function delete(string $id): bool
    {
        return true;
    }

    private function toCategoryEntity(object $data): CategoryEntity
    {
        return new CategoryEntity(
            id: $data->id,
            name: $data->name
        );
    }
}
