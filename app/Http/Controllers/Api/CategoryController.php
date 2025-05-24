<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;
use Core\UseCase\Category\{
    CreateCategoryUseCase,
    ListCategoriesUseCase,
    ListCategoryUseCase
};
use Core\UseCase\DTO\Category\{
    CategoryCreateInputDTO,
    CategoryInputDTO,
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

        return CategoryResource::collection(
            collect($responseUseCase->items)
        )->additional($meta);
    }

    public function store(StoreCategoryRequest $request, CreateCategoryUseCase $useCase)
    {
        $responseUseCase = $useCase->execute(
            input: new CategoryCreateInputDTO(
                $request->name,
                $request->description,
            )
        );

        return (new CategoryResource(collect($responseUseCase)))
            ->additional(['message' => 'Category created successfully'])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show($id, ListCategoryUseCase $useCase)
    {
        $responseUseCase = $useCase->execute(new CategoryInputDTO(id: $id));

        return (new CategoryResource(collect($responseUseCase)))->response();
    }
}
