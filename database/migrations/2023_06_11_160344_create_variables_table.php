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
        Schema::create('variables', static function (Blueprint $table) {
            $table->string('name')->primary();
            $table->json('value');
        });
    }
};
