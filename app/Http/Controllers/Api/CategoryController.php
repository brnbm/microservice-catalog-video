<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\DTO\Category\ListCategoriesInputDTO;

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
}
