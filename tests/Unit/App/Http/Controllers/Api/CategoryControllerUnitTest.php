<?php

namespace Tests\Unit\App\Http\Controllers\Api;

use Mockery;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Api\CategoryController;
use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\DTO\Category\ListCategoriesOutputDTO;

class CategoryControllerUnitTest extends TestCase
{
    #[Test]
    public function index(): void
    {
        $mockRequest = Mockery::mock(Request::class);
        $mockRequest->shouldReceive('get')->andReturn('Category API - Test Success!');

        $mockOutputDTO = Mockery::mock(ListCategoriesOutputDTO::class, [[], 1, 1, 1, 1, 1, 1, 1]);

        $mockUseCase = Mockery::mock(ListCategoriesUseCase::class);
        $mockUseCase->shouldReceive('execute')->andReturn($mockOutputDTO);

        $controller = new CategoryController();
        $response = $controller->index($mockRequest, $mockUseCase);

        $this->assertIsObject($response->resource);
        $this->assertArrayHasKey('meta', $response->additional);
    }
}
