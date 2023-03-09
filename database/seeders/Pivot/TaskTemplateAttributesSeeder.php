<?php

namespace Database\Seeders\Pivot;

use App\Models\Project;
use App\Models\TaskAttribute;
use App\Models\TaskTemplate;
use DateTime;
use Illuminate\Database\Seeder;

class TaskTemplateAttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $taskTemplates = TaskTemplate::limit(2)->get();
        $attributes = TaskAttribute::all();

        foreach ($taskTemplates as $task) {
            $task->taskAttributes()->attach($attributes[0]->id, ['value' => "$task->id task"]);
            $task->taskAttributes()->attach($attributes[1]->id, ['value' => "$task->id description"]);
            $task->taskAttributes()->attach($attributes[2]->id, ['value' => (new DateTime())->modify('5 day')]);
        }
    }
}
