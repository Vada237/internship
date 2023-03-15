<?php

namespace Database\Factories;

use App\Models\Board;
use App\Models\BoardTemplate;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Board>
 */
class BoardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => BoardTemplate::inRandomOrder()->first()->name,
            'project_id' => Project::inRandomOrder()->first()->id,
            'status' => Board::statuses['EDITED']
        ];
    }
}
