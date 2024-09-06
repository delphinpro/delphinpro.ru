<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2024.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('links', static function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('url');
            $table->boolean('published')->default(true)->index();
            $table->string('cover')->nullable();
            $table->string('background')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
