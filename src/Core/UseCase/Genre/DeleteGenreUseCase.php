<?php

namespace Core\UseCase\Genre;

use Core\UseCase\DTO\Genre\GenreInputDTO;
use Core\Domain\Repository\GenreRepositoryInterface;

class DeleteGenreUseCase
{
    public function __construct(private GenreRepositoryInterface $repository) {}

    public function execute(GenreInputDTO $input): bool
    {
        $category = $this->repository->findById($input->id);
        return $this->repository->delete($category->id());
    }
}
