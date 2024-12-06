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
        Schema::table('articles', static function (Blueprint $table) {
            $table->after('published', function (Blueprint $table) {
                $table->boolean('show_on_main')->default(false)->index();
                $table->boolean('show_in_list')->default(false)->index();
            });
        });
    }

    public function down(): void
    {
        Schema::table('articles', static function (Blueprint $table) {
            $table->dropColumn('show_on_main');
            $table->dropColumn('show_in_list');
        });
    }
};
