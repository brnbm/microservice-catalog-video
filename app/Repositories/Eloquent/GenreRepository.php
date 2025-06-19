<?php

namespace App\Repositories\Eloquent;

use Core\Domain\ValueObject\Uuid;
use App\Models\Genre as GenreModel;
use Core\Domain\Entity\GenreEntity;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Exception\NotFoundDomainException;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Repository\GenreRepositoryInterface;

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
            throw new NotFoundDomainException("Genre [{$id}] not found.");
        }

        return $this->toGenreEntity($data);
    }

    public function getListIdsByIds(array $ids = []): array
    {
        return $this->model->whereIn('id', $ids)->pluck('id')->toArray();
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
            throw new NotFoundDomainException("Genre [{$entity->id}] not found.");
        }

        $data->update([
            'name' => $entity->name,
            'is_active' => $entity->isActive
        ]);

        if (count($entity->categoriesId) > 0) {
            $data->categories()->sync($entity->categoriesId);
        }

        return $this->toGenreEntity($data);
    }

    public function delete(string $id): bool
    {
        $data = $this->model->find($id);

        if (is_null($data)) {
            throw new NotFoundDomainException("Genre [{$id}] not found.");
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
            createdAt: new \DateTime($data->created_at),
            updatedAt: new \DateTime($data->update_at)
        );
    }
}
