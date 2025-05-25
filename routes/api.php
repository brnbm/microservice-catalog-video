<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{CategoryController};

Route::get('/', function () {
    return response()->json(['message' => 'Welcome to the BRUNO API!']);
});

Route::resources(
    ['categories' => CategoryController::class],
);
