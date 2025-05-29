<?php

namespace Core\UseCase\Category;

use Core\UseCase\DTO\Category\CategoryOutputDTO;
use Core\UseCase\DTO\Category\CategoryUpdateInputDTO;
use Core\Domain\Repository\CategoryRepositoryInterface;

class UpdateCategoryUseCase
{
    public function __construct(private CategoryRepositoryInterface $repository) {}

    public function execute(CategoryUpdateInputDTO $input): CategoryOutputDTO
    {
        $category = $this->repository->findById($input->id);
        $category->update(
            $input->name,
            $input->description ?? $category->description,
            $input->isActive
        );

        $updatedCategory = $this->repository->update($category);

        return new CategoryOutputDTO(
            id: $updatedCategory->id,
            name: $updatedCategory->name,
            description: $updatedCategory->description,
            is_active: $updatedCategory->isActive,
            created_at: $updatedCategory->createdAt(),
            updated_at: $updatedCategory->updatedAt()
        );
    }
}
