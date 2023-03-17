<?php

namespace Database\Factories;

use App\Models\TaskTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubtaskTemplate>
 */
class SubtaskTemplateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->word,
            'task_template_id' => TaskTemplate::inRandomOrder()->first()
        ];
    }
}
