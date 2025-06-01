<?php

namespace Tests;

use Mockery;
use Core\Domain\Repository\PaginationInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected function generateMockPagination(array $items = [])
    {
        $mockPagination = Mockery::mock(\stdClass::class, PaginationInterface::class);
        $mockPagination->shouldReceive('items')->andReturn($items);
        $mockPagination->shouldReceive('total')->andReturn(0);
        $mockPagination->shouldReceive('lastPage')->andReturn(0);
        $mockPagination->shouldReceive('firstPage')->andReturn(0);
        $mockPagination->shouldReceive('currentPage')->andReturn(0);
        $mockPagination->shouldReceive('perPage')->andReturn(0);
        $mockPagination->shouldReceive('to')->andReturn(0);
        $mockPagination->shouldReceive('from')->andReturn(0);

        return $mockPagination;
    }
}
