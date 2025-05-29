<?php

namespace Core\UseCase\DTO\Category;

class CategoryUpdateInputDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $description = null,
        public bool $isActive = true
    ) {}
}
