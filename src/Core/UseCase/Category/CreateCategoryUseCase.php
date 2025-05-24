<?php

namespace Core\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\UseCase\DTO\Category\CategoryCreateInputDTO;
use Core\UseCase\DTO\Category\CategoryOutputDTO;
use Core\Domain\Repository\CategoryRepositoryInterface;

/**
 * Use Case:
 * * Representa um processo específico da aplicação.
 * * Nesse caso, criar uma nova categoria.
 */
class CreateCategoryUseCase
{
    public function __construct(private CategoryRepositoryInterface $repository) {}

    public function execute(CategoryCreateInputDTO $input): CategoryOutputDTO
    {
        $category = new Category(
            name: $input->name,
            description: $input->description,
            isActive: $input->isActive
        );
        $newCategory = $this->repository->insert($category);

        return new CategoryOutputDTO(
            id: $newCategory->id(),
            name: $newCategory->name,
            description: $newCategory->description,
            is_active: $newCategory->isActive,
            created_at: $newCategory->createdAt()
        );
    }
}
