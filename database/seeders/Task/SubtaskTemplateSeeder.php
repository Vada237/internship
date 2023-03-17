<?php

namespace Database\Seeders\Task;

use App\Models\SubtaskTemplate;
use Illuminate\Database\Seeder;

class SubtaskTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubtaskTemplate::factory()->count(20)->create();
    }
}
