<?php

namespace  Core\Domain\Validation;

use Core\Domain\Exception\EntityValidationException;

class DomainValidation
{
    public static function notNull(string $value, ?string $exceptionMessage = null): void
    {
        if (empty($value))
            throw new EntityValidationException($exceptionMessage ?? 'Value is required.');
    }

    public static function strMinlength(string $value, int $length = 255, ?string $exceptionMessage = null): void
    {
        if (strlen($value) < $length)
            throw new EntityValidationException($exceptionMessage ?? "The value must not be less than {$length} characters.");
    }

    public static function strMaxlength(string $value, int $length = 255, ?string $exceptionMessage = null): void
    {
        if (strlen($value) > $length)
            throw new EntityValidationException($exceptionMessage ?? "The value must not be greater than {$length} characters.");
    }
}
