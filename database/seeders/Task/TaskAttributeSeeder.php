<?php

namespace Database\Seeders\Task;

use App\Models\TaskAttribute;
use Illuminate\Database\Seeder;

class TaskAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaskAttribute::create([
            'name' => 'Name'
        ]);

        TaskAttribute::create([
            'name' => 'Description'
        ]);

        TaskAttribute::create([
            'name' => 'Deadline'
        ]);

        TaskAttribute::create([
           'name' => 'Image'
        ]);
    }
}
