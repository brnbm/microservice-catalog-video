<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['id', 'name', 'description', 'is_active'];

    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
        'is_active' => 'boolean'
    ];
}
