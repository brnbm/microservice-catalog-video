<?php

namespace Tests\Unit\App\Models;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GenreUnitTest extends ModelTestCase
{
    private Model $model;

    protected function getModel(): Model
    {
        return $this->model ??= new Genre();
    }

    protected function getTraits(): array
    {
        return [
            HasFactory::class,
            SoftDeletes::class
        ];
    }

    protected function getFillables(): array
    {
        return [
            'id',
            'name',
            'is_active'
        ];
    }

    protected function getCasts(): array
    {
        return [
            'id' => 'string',
            'is_active' => 'boolean',
            'deleted_at' => 'datetime'
        ];
    }
}
