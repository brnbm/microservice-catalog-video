<?php

namespace Core\Domain\Entity;

use Core\Domain\ValueObject\Uuid;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\Entity\Traits\MethodsMagicsTrait;

/**
 * Entidade:
 * * Representa um objeto do mundo real no sistema e contém a lógica de negócio.
 */
class GenreEntity
{
    use MethodsMagicsTrait;

    public function __construct(
        protected string $name,
        protected ?Uuid $id = null,
        protected array $categoriesId = [],
        protected bool $isActive = true,
        protected ?\DateTime $createdAt = null,
        protected \DateTime|string $updatedAt = '',

    ) {
        $this->id = $this->id ?? Uuid::random();
        $this->createdAt = $this->createdAt ?? new \DateTime();
        $this->validate();
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }

    public function update(string $name): void
    {
        $this->name = $name;
        $this->validate();
    }

    public function addCategory(string $categoryId): void
    {
        $this->categoriesId[] = $categoryId;
    }

    public function removeCategory(string $categoryId): void
    {
        $this->categoriesId = array_filter(
            $this->categoriesId,
            fn($id) => $id !== $categoryId
        );
    }

    protected function validate()
    {
        DomainValidation::notNull($this->name, 'Name is required.');
        DomainValidation::strMinlength($this->name, 3, 'Name must be at least 3 characters.');
        DomainValidation::strMaxlength($this->name, 60, 'Name must be a maximum of 60 characters.');
    }
}
