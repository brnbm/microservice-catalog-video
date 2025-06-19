<?php

namespace Core\UseCase\Genre;

use Core\Domain\Entity\GenreEntity;
use Core\Domain\Exception\NotFoundDomainException;
use Core\UseCase\Interfaces\TransactionInterface;
use Core\UseCase\DTO\Genre\{GenreCreateInputDTO, GenreOutputDTO};
use Core\Domain\Repository\{CategoryRepositoryInterface, GenreRepositoryInterface};

/**
 * Use Case:
 * * Representa um processo específico da aplicação.
 * * Nesse caso, criar um novo gênero.
 */
class CreateGenreUseCase
{
    public function __construct(
        private GenreRepositoryInterface $repository,
        private CategoryRepositoryInterface $categoryRepository,
        private TransactionInterface $transaction
    ) {}

    public function execute(GenreCreateInputDTO $input): GenreOutputDTO
    {
        try {
            $genreEntity = new GenreEntity(
                name: $input->name,
                categoriesId: $input->categoriesId,
                isActive: $input->is_active,
            );

            $this->validateCategoriesId($input->categoriesId);
            $response = $this->repository->insert($genreEntity);
            $this->transaction->commit();

            return new GenreOutputDTO(
                id: $response->id,
                name: $response->name,
                categoriesId: $response->categoriesId,
                is_active: $response->isActive,
                created_at: $response->createdAt(),
            );
        } catch (\Throwable $th) {
            $this->transaction->rollback();
            throw $th;
        }
    }

    public function validateCategoriesId(array $categoriesId): void
    {
        $categories = $this->categoryRepository->getListIdsByIds($categoriesId);
        $arrayDiff = array_diff($categoriesId, $categories);

        if (count($arrayDiff)) {
            $msg = sprintf(
                "%s [%s] not found.",
                count($arrayDiff) > 1 ? "Categories" : "Category",
                implode(', ', $arrayDiff)
            );
            throw new NotFoundDomainException($msg);
        }
    }
}
