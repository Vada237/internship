<?php

namespace Database\Seeders\Task;

use App\Models\TaskTemplate;
use Illuminate\Database\Seeder;

class TaskTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaskTemplate::factory()->count(15)->create();
    }
}
