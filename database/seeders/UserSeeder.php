<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * @throws \JsonException
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name'        => 'delphinpro',
            'email'       => 'delphinpro@yandex.ru',
            'trust_level' => 255,
            'settings'    => [
                'timezone' => 'Europe/Samara',
            ],
        ]);

        DB::table('roles')->insert([
            'name'        => 'Admin',
            'slug'        => 'admin',
            'permissions' => json_encode([
                'platform.index'              => 1,
                'platform.systems.roles'      => 1,
                'platform.systems.users'      => 1,
                'platform.systems.attachment' => 1,
            ], JSON_THROW_ON_ERROR),
        ]);

        DB::table('role_users')->insert([
            'role_id' => 1,
            'user_id' => $admin->id,
        ]);

        User::factory()->create([
            'name'  => 'user',
            'email' => 'user@example.com',
        ]);

        User::factory()->count(config('seed.users_count', 30))->create();
    }
}
