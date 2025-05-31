<?php

namespace Core\Domain\Repository;

use Core\Domain\Entity\GenreEntity;

interface GenreRepositoryInterface
{
    public function insert(GenreEntity $genre): GenreEntity;
    public function findById(string $id): GenreEntity;
    public function findAll(string $filter = '', string $order = 'DESC'): array;
    public function paginate(string $filter = '', string $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface;
    public function update(GenreEntity $category): GenreEntity;
    public function delete(string $id): bool;
}
