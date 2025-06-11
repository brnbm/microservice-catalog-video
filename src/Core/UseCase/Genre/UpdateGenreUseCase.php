<?php

namespace Core\UseCase\Genre;

use Core\Domain\Exception\NotFoundDomainException;
use Core\UseCase\Interfaces\TransactionInterface;
use Core\UseCase\DTO\Genre\{GenreUpdateInputDTO, GenreOutputDTO};
use Core\Domain\Repository\{CategoryRepositoryInterface, GenreRepositoryInterface};

/**
 * Use Case:
 * * Representa um processo específico da aplicação.
 * * Nesse caso, criar um novo gênero.
 */
class UpdateGenreUseCase
{
    public function __construct(
        private GenreRepositoryInterface $repository,
        private CategoryRepositoryInterface $categoryRepository,
        private TransactionInterface $transaction
    ) {}

    public function execute(GenreUpdateInputDTO $input): GenreOutputDTO
    {
        $genre = $this->repository->findById($input->id);

        $genre->update($input->name);

        if (count($input->categoriesId)) {
            $this->validateCategoriesId($input->categoriesId);

            foreach ($input->categoriesId as $categoryId) {
                $genre->addCategory($categoryId);
            }
        }

        $response = $this->repository->update($genre);

        return new GenreOutputDTO(
            id: $response->id,
            name: $response->name,
            categoriesId: $response->categoriesId,
            is_active: $response->isActive,
            created_at: $response->createdAt(),
            updated_at: $response->updatedAt()
        );
    }

    public function validateCategoriesId(array $categoriesId): void
    {
        $categories = $this->categoryRepository->getListIdsByIds($categoriesId);

        $arrayDiff = array_diff($categoriesId, $categories);

        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found.',
                count($arrayDiff) > 1 ? 'Categories' : 'Category',
                implode(', ', $arrayDiff)
            );

            throw new NotFoundDomainException($msg);
        }
    }
}
