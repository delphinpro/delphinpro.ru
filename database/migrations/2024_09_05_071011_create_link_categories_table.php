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
        Schema::create('link_categories', static function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('link_categories');
    }
};
