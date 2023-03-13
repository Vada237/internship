<?php

namespace Database\Seeders\Pivot;

use App\Models\Project;
use App\Models\SubtaskTemplate;
use App\Models\Attribute;
use App\Models\TaskTemplate;
use DateTime;
use Illuminate\Database\Seeder;

class SubtasksAttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subtasks = SubtaskTemplate::limit(10)->get();
        $attributes = Attribute::all();

        foreach ($subtasks as $subtask) {
            $subtask->attributes()->attach($attributes[0]->id, ['value' => implode(fake()->words)]);
            $subtask->attributes()->attach($attributes[1]->id, ['value' => (new DateTime())->modify('5 day')]);
        }
    }
}
