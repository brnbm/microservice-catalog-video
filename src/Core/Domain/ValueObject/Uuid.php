<?php

namespace Core\Domain\ValueObject;

use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid
{
    public function __construct(
        protected string $value
    ) {
        $this->validate();
    }

    public static function random(): self
    {
        return new self(RamseyUuid::uuid4()->toString());
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function validate()
    {
        if (! RamseyUuid::isValid($this->value))
            throw new \InvalidArgumentException("Invalid Uuid: {$this->value}");
    }
}
