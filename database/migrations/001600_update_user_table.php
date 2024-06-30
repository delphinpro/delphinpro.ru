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
        Schema::table('users', static function (Blueprint $table) {
            $table->after('password', function (Blueprint $table) {
                $table->integer('trust_level')->default(0);
            });
        });
    }

    public function down(): void
    {
        Schema::dropColumns('users', ['trust_level']);
    }
};
