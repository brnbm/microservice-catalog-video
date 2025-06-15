<?php

namespace App\Repositories\Eloquent;


use App\Models\Category as CategoryModel;
use App\Models\Genre as GenreModel;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Entity\Category as CategoryEntity;
use Core\Domain\Exception\NotFoundDomainException;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entity\GenreEntity;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use DateTime;

/**
 * Repository:
 * * Abstrai a persistência de dados.
 */
class GenreRepository implements GenreRepositoryInterface
{
    public function __construct(private GenreModel $model) {}

    public function insert(GenreEntity $entity): GenreEntity
    {
        $data = $this->model->create([
            'id' => $entity->id,
            'name' => $entity->name,
            'is_active' => $entity->isActive,
            'created_at' => $entity->createdAt
        ]);

        if (count($entity->categoriesId) > 0) {
            $data->categories()->sync($entity->categoriesId);
        }

        return $this->toGenreEntity($data);
    }

    public function findById(string $id): GenreEntity
    {
        $data = $this->model->find($id);

        if (is_null($data)) {
            throw new NotFoundDomainException('Category not found.');
        }

        return $this->toGenreEntity($data);
    }

    public function getListIdsByIds(array $ids = []): array
    {
        return $this->model->whereIn('id', $ids)->pluck('id');
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

    public function update(GenreEntity $entity): GenreEntity
    {
        $data = $this->model->find($entity->id);

        if (is_null($data)) {
            throw new NotFoundDomainException('Genre not found.');
        }

        $data->update([
            'name' => $entity->name,
            'description' => $entity->description,
            'is_active' => $entity->isActive
        ]);

        return $this->toGenreEntity($data);
    }

    public function delete(string $id): bool
    {
        $data = $this->model->find($id);

        if (is_null($data)) {
            throw new NotFoundDomainException('Genre not found.');
        }

        return $data->delete();
    }

    private function toGenreEntity(object $data): GenreEntity
    {
        return new GenreEntity(
            name: $data->name,
            id: new Uuid($data->id),
            categoriesId: $data->categories->pluck('id')->toArray(),
            isActive: $data->is_active,
            createdAt: new DateTime($data->created_at),
            updatedAt: new DateTime($data->update_at)
        );
    }
}
