<?php

namespace Core\UseCase\Category;

use Core\UseCase\DTO\Category\CategoryInputDTO;
use Core\Domain\Repository\CategoryRepositoryInterface;

class DeleteCategoryUseCase
{
    public function __construct(private CategoryRepositoryInterface $repository) {}

    public function execute(CategoryInputDTO $input): bool
    {
        $category = $this->repository->findById($input->id);
        return $this->repository->delete($category->id());
    }
}
