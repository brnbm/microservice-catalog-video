<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('category_genre', function (Blueprint $table) {

            $table->uuid('category_id')->index();
            $table->foreign('category_id')
                ->references('id')
                ->on('categories');

            $table->uuid('genre_id')->index();
            $table->foreign('genre_id')
                ->references('id')
                ->on('genres');

            $table->unique(['category_id', 'genre_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_genre');
    }
};
