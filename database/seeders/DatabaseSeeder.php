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
            'tg_id' => 1059208615,
            'username' => 'leorlik',
            'first_name' => 'Kirill',
            'last_name' => 'Orlov',
            'language' => 'ru',
            'role_id' => 1,
            'mail' => 'leorl1k@yandex.ru',
            'number' => '+7',
            'is_premium' => $from->is_premium ?? false,
            'is_blocked' => $from->is_blocked ?? false,
        ]);
        Tab::factory(1)->create();
    }
}
