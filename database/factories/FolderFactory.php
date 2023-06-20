<?php

namespace Database\Factories;

use App\Models\Folder;
use App\Models\Tab;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Folder>
 */
class FolderFactory extends Factory
{
    protected $model = Folder::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    private static int $int = 0;
    public function definition(): array
    {
        self::$int++;
        return [
            'tab_id' => Tab::first()->id,
            'name' => '📚 ' . $this->faker->unique()->name(),
            'sorted_id' => self::$int,
        ];
    }
}
