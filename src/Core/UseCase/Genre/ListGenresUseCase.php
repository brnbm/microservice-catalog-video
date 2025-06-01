<?php

namespace Core\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\{ListGenresInputDTO, ListGenresOutputDTO};

class ListGenresUseCase
{
    public function __construct(private GenreRepositoryInterface $repository) {}

    public function execute(ListGenresInputDTO $input): ListGenresOutputDTO
    {
        $response = $this->repository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPage
        );

        return new ListGenresOutputDTO(
            items: $response->items(),
            total: $response->total(),
            last_page: $response->lastPage(),
            first_page: $response->firstPage(),
            current_page: $response->currentPage(),
            per_page: $response->perPage(),
            to: $response->to(),
            from: $response->from()
        );
    }
}
