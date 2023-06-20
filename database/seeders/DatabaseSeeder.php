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
        Role::factory(1)->create();
        User::factory(30)->create();
        Tab::factory(1)->create();
        Tab::factory(1)->create([
            'name' => 'AdminRecursion'
        ]);
        Folder::factory(3)->create();
        Button::factory(5)->create();
    }
}
