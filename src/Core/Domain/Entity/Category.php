<?php

namespace Core\Domain\Entity;

use Core\Domain\ValueObject\Uuid;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\Entity\Traits\MethodsMagicsTrait;
use DateTime;

/**
 * Entidade:
 * * Representa um objeto do mundo real no sistema e contém a lógica de negócio.
 */
class Category
{
    use MethodsMagicsTrait;

    public function __construct(
        protected Uuid|string $id = '',
        protected string $name = '',
        protected string $description = '',
        protected bool $isActive = true,
        protected DateTime|string $createdAt = '',
        protected DateTime|string $updatedAt = ''
    ) {
        $this->id = $this->id ? new Uuid($this->id) : Uuid::random();
        $this->createdAt = $this->createdAt ? new DateTime($this->createdAt) : new DateTime();
        $this->validate();
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function disable(): void
    {
        $this->isActive = false;
    }

    public function update(string $name, string $description = '', bool $isActive = true): void
    {
        $this->name = $name;
        $this->description = $description ?? $this->description;
        $this->isActive = $isActive ?? $this->isActive;
        $this->validate();
    }

    private function validate()
    {
        DomainValidation::notNull($this->name, 'Name is required.');
        DomainValidation::strMinlength($this->name, 3, 'Name must be at least 3 characters.');
        DomainValidation::strMaxlength($this->name, 60, 'Name must be a maximum of 60 characters.');
    }
}
