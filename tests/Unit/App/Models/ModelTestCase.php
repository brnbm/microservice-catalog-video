<?php

namespace Tests\Unit\App\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Database\Eloquent\Model;

abstract class ModelTestCase extends TestCase
{
    abstract protected function getModel(): Model;
    abstract protected function getTraits(): array;
    abstract protected function getFillables(): array;
    abstract protected function getCasts(): array;

    #[Test]
    public function useTraits(): void
    {
        $traitsUsed = array_keys(class_uses($this->getModel()));
        $this->assertEquals($this->getTraits(), $traitsUsed);
    }

    #[Test]
    public function incrementingIsFalse(): void
    {
        $this->assertFalse($this->getModel()->getIncrementing());
    }

    #[Test]
    public function hasFillable(): void
    {
        $this->assertEquals($this->getFillables(), $this->getModel()->getFillable());
    }

    #[Test]
    public function hasCasts(): void
    {
        $this->assertEquals($this->getCasts(), $this->getModel()->getCasts());
    }
}
