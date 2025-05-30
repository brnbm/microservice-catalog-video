<?php

namespace Core\UseCase\Category;

use Core\UseCase\DTO\Category\CategoryInputDTO;
use Core\UseCase\DTO\Category\CategoryOutputDTO;
use Core\Domain\Repository\CategoryRepositoryInterface;

class ListCategoryUseCase
{
    public function __construct(private CategoryRepositoryInterface $repository) {}

    public function execute(CategoryInputDTO $input): CategoryOutputDTO
    {
        $category = $this->repository->findById($input->id);

        return new CategoryOutputDTO(
            id: $category->id,
            name: $category->name,
            description: $category->description,
            is_active: $category->isActive,
            created_at: $category->createdAt(),
            updated_at: $category->updatedAt()
        );
    }
}
