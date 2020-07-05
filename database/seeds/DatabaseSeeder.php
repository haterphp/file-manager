<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        collect(['admin', 'user'])->each(function ($item) {\App\Role::create(['name' => $item]);});

        \App\User::create([
            'login' => 'administrateur',
            'password' => \Illuminate\Support\Facades\Hash::make('mot de passe'),
            'role' => 1
        ]);

        \App\Dir::create([
            'name' => 'root'
        ]);
    }
}
