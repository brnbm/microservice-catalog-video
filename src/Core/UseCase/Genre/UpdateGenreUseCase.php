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
        try {
            $genre = $this->repository->findById($input->id);

            $genre->update($input->name);
            $input->is_active ? $genre->activate() : $genre->deactivate();

            if (count($input->categoriesId)) {
                $this->validateCategoriesId($input->categoriesId);

                foreach ($input->categoriesId as $categoryId) {
                    $genre->addCategory($categoryId);
                }
            }

            $responseUseCase = $this->repository->update($genre);
            $this->transaction->commit();

            return new GenreOutputDTO(
                id: $responseUseCase->id,
                name: $responseUseCase->name,
                categoriesId: $responseUseCase->categoriesId,
                is_active: $responseUseCase->isActive,
                created_at: $responseUseCase->createdAt(),
                updated_at: $responseUseCase->updatedAt()
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
                '%s [%s] not found.',
                count($arrayDiff) > 1 ? 'Categories' : 'Category',
                implode(', ', $arrayDiff)
            );

            throw new NotFoundDomainException($msg);
        }
    }
}
