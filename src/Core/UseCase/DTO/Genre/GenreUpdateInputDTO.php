<?php

namespace Core\UseCase\DTO\Genre;

class GenreUpdateInputDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public array $categoriesId = [],
        public bool $is_active = true
    ) {}
}
