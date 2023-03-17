<?php

namespace Database\Seeders\Board;

use App\Models\BoardTemplate;
use Illuminate\Database\Seeder;

class BoardTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BoardTemplate::factory()->count(5)->create();
    }
}
