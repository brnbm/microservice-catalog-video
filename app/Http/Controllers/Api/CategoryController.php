<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\{
    StoreCategoryRequest,
    UpdateCategoryRequest
};
use Core\UseCase\Category\{
    CreateCategoryUseCase,
    DeleteCategoryUseCase,
    ListCategoriesUseCase,
    ListCategoryUseCase,
    UpdateCategoryUseCase
};
use Core\UseCase\DTO\Category\{
    CategoryCreateInputDTO,
    CategoryInputDTO,
    CategoryUpdateInputDTO,
    ListCategoriesInputDTO
};
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function index(Request $request, ListCategoriesUseCase $useCase)
    {
        $responseUseCase = $useCase->execute(
            input: new ListCategoriesInputDTO(
                filter: $request->get('filter', ''),
                order: $request->get('order', 'DESC'),
                page: (int) $request->get('page', 1),
                totalPage: (int) $request->get('totalPage', 10)
            )
        );

        $meta = [];
        foreach ($responseUseCase as $key => $value) {
            if ($key !== 'items') {
                $meta['meta'][$key] = $value;
            }
        }

        return CategoryResource::collection($responseUseCase->items)->additional($meta);
    }

    public function store(StoreCategoryRequest $request, CreateCategoryUseCase $useCase)
    {
        $responseUseCase = $useCase->execute(
            input: new CategoryCreateInputDTO(
                $request->name,
                $request->description,
            )
        );

        return (new CategoryResource($responseUseCase))
            ->additional(['message' => 'Category created successfully'])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show($id, ListCategoryUseCase $useCase)
    {
        $responseUseCase = $useCase->execute(new CategoryInputDTO(id: $id));

        return (new CategoryResource($responseUseCase))->response();
    }

    public function update($id, UpdateCategoryRequest $request, UpdateCategoryUseCase $useCase)
    {
        $responseUseCase = $useCase->execute(
            new CategoryUpdateInputDTO(
                id: $id,
                name: $request->name,
                description: $request->description,
                isActive: $request->is_active
            )
        );

        return (new CategoryResource($responseUseCase))->response();
    }

    public function destroy($id, DeleteCategoryUseCase $useCase)
    {
        $useCase->execute(new CategoryInputDTO(id: $id));

        return response()->noContent();
    }
}
