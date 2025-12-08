<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(5),
            'content' => fake()->paragraphs(3, true),
            'is_published' => fake()->boolean(80),
        ];
    }
}
