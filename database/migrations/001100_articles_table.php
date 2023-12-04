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
        Schema::create('articles', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cover_id')->nullable();
            $table->boolean('published')->default(true);
            $table->string('title');
            $table->text('summary')->nullable();
            $table->text('content')->nullable();
            $table->json('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['user_id']);
            $table->index(['published']);
            $table->index(['created_at']);
            $table->index(['deleted_at']);
        });
    }
};
