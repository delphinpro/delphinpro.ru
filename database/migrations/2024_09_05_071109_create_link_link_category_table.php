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
        Schema::create('link_link_category', static function (Blueprint $table) {
            $table->unsignedBigInteger('link_id');
            $table->unsignedBigInteger('link_category_id');
            $table->primary(['link_id', 'link_category_id']);

            $table->foreign('link_id')->references('id')->on('links')->onDelete('cascade');
            $table->foreign('link_category_id')->references('id')->on('link_categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('link_link_category');
    }
};
