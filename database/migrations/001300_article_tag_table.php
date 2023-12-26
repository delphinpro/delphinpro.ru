<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('article_tag', static function (Blueprint $table) {
            $table->unsignedBigInteger('article_id');
            $table->unsignedBigInteger('tag_id');

            $table->primary(['article_id', 'tag_id']);

            $table->foreign('article_id')
                ->on('articles')->references('id')
                ->cascadeOnDelete();

            $table->foreign('tag_id')
                ->on('tags')->references('id')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_tag');
    }
};
