<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model:
 * * Representa os dados da aplicação e como eles são armazenados no banco de dados.
 */
class Genre extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;

    protected $fillable = ['id', 'name', 'is_active'];

    protected $casts = [
        'id' => 'string',
        'is_active' => 'boolean'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
