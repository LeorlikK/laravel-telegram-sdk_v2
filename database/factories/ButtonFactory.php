<?php

namespace Database\Factories;

use App\Models\Button;
use App\Models\Folder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Button>
 */
class ButtonFactory extends Factory
{
    protected $model = Button::class;
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
            'folder_id' => Folder::first()->id,
            'text' => 'button' . self::$int,
            'callback' => 'some_callback',
            'sorted_id' => self::$int
        ];
    }
}
