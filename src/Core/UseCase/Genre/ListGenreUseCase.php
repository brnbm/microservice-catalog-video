<?php

namespace Core\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\{GenreInputDTO, GenreOutputDTO};

class ListGenreUseCase
{
    public function __construct(private GenreRepositoryInterface $repository) {}

    public function execute(GenreInputDTO $input): GenreOutputDTO
    {
        $response = $this->repository->findById($input->id);

        return new GenreOutputDTO(
            id: $response->id,
            name: $response->name,
            categoriesId: $response->categoriesId,
            is_active: $response->isActive,
            created_at: $response->createdAt(),
            updated_at: $response->updatedAt()
        );
    }
}
