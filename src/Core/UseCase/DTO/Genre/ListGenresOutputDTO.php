<?php

namespace Core\UseCase\DTO\Genre;

class ListGenresOutputDTO
{
    public function __construct(
        public array $items,
        public int $total = 0,
        public int $last_page = 0,
        public int $first_page = 0,
        public int $current_page = 0,
        public int $per_page = 0,
        public int $to = 0,
        public int $from = 0,
    ) {}
}
