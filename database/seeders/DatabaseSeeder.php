<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user= \App\Models\Employ::create([
            'name' =>'makrem',
            'email' => 'makrem050@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123456789'), // password
        ]);
        Role::create(['name' => 'Super_Admin','guard_name' => 'employs']);
        $user->assignRole('Super_Admin');

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
