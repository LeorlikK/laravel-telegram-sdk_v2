<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Button;
use App\Models\Folder;
use App\Models\Role;
use App\Models\Tab;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Role::create([
            'name' => 'admin',
            'visibility' => 100,
        ]);
        Role::create([
            'name' => 'user',
            'visibility' => 0,
        ]);
//        User::factory(30)->create();
        User::create([
            'tg_id' => env('ADMIN_TG_ID'),
            'username' => env('ADMIN_USERNAME'),
            'first_name' => env('ADMIN_FIRST_NAME'),
            'last_name' => env('ADMIN_LAST_NAME'),
            'language' => env('ADMIN_LANGUAGE'),
            'role_id' => env('ADMIN_ROLE_ID'),
            'mail' => env('ADMIN_MAIL'),
            'number' => env('ADMIN_NUMBER'),
            'is_blocked' => $from->is_blocked ?? false,
        ]);
        Tab::factory(1)->create();
    }
}
