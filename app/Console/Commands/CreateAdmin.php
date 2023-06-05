<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Command\Command as BaseCommand;

class CreateAdmin extends Command
{
    protected $signature = 'user:create
                           {name : User name}
                           {email : E-Mail address}
                           {password : Password}';

    protected $description = 'Create admin user';

    public function handle(): int
    {
        $user = User::make([
            'name'     => $this->argument('name'),
            'email'    => $this->argument('email'),
            'password' => Hash::make($this->argument('password')),
            'role_id'  => 1,
            'position' => 'Администратор',
        ]);

        $user->email_verified_at = now();
        $user->save();

        $this->info('Admin created successfully');

        return BaseCommand::SUCCESS;
    }
}
