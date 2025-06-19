<?php

namespace App\Repositories\Eloquent;


use App\Models\Category as CategoryModel;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Entity\Category as CategoryEntity;
use Core\Domain\Exception\NotFoundDomainException;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Repository\CategoryRepositoryInterface;

/**
 * Repository:
 * * Abstrai a persistência de dados.
 */
class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(private CategoryModel $model) {}

    public function insert(CategoryEntity $entity): CategoryEntity
    {
        $data = $this->model->create([
            'id' => $entity->id(),
            'name' => $entity->name,
            'description' => $entity->description,
            'is_active' => $entity->isActive,
            'created_at' => $entity->createdAt
        ]);

        return $this->toCategoryEntity($data);
    }

    public function findById(string $id): CategoryEntity
    {
        $data = $this->model->find($id);

        if (is_null($data)) {
            throw new NotFoundDomainException("Category [{$id}] not found.");
        }

        return $this->toCategoryEntity($data);
    }

    public function getListIdsByIds(array $ids = []): array
    {
        return $this->model->whereIn('id', $ids)->pluck('id')->toArray();;
    }

    public function findAll(string $filter = '', string $order = 'DESC'): array
    {
        $categories = $this->model
            ->when(!empty($filter), fn($query) => $query->where('name', 'like', "%$filter%"))
            ->orderBy('id', $order)
            ->get();

        return $categories->toArray();
    }

    public function paginate(string $filter = '', string $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        $paginator = $this->model
            ->when(!empty($filter), fn($query) => $query->where('name', 'like', "%$filter%"))
            ->orderBy('id', $order)
            ->paginate($totalPage);

        return new PaginationPresenter($paginator);
    }

    public function update(CategoryEntity $entity): CategoryEntity
    {
        $data = $this->model->find($entity->id);

        if (is_null($data)) {
            throw new NotFoundDomainException('Category not found.');
        }

        $data->update([
            'name' => $entity->name,
            'description' => $entity->description,
            'is_active' => $entity->isActive
        ]);

        return $this->toCategoryEntity($data);
    }

    public function delete(string $id): bool
    {
        $data = $this->model->find($id);

        if (is_null($data)) {
            throw new NotFoundDomainException("Category [{$id}] not found.");
        }

        return $data->delete();
    }

    private function toCategoryEntity(object $data): CategoryEntity
    {
        return new CategoryEntity(
            id: $data->id,
            name: $data->name,
            description: $data->description,
            isActive: $data->is_active,
            createdAt: $data->created_at,
            updatedAt: $data->updated_at
        );
    }
}
