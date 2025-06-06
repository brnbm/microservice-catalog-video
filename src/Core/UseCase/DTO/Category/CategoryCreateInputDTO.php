<?php

namespace Core\UseCase\DTO\Category;

class CategoryCreateInputDTO
{
    public function __construct(
        public string $name,
        public ?string $description = '',
        public bool $isActive = true
    ) {}
}
