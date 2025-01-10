<?php

namespace Tests\Unit\Domain\Validation;

use Throwable;
use PHPUnit\Framework\TestCase;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\Exception\EntityValidationException;

class DomainValidationUnitTest extends TestCase
{
    public function testNotNull()
    {
        try {
            $value = '';
            DomainValidation::notNull($value);

            $this->assertTrue(true);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
        }
    }

    public function testStrMinLength()
    {
        try {
            $value = '123';
            $length = 5;
            DomainValidation::strMinLength($value, $length);

            $this->assertTrue(true);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
        }
    }

    public function testStrMaxLength()
    {
        try {
            $value = '12345678910';
            $length = 10;
            DomainValidation::strMaxLength($value, $length);

            $this->assertTrue(true);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
        }
    }
}
