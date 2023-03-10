<?php

namespace Database\Seeders\Attribute;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Attribute::create([
            'name' => 'Name'
        ]);

        Attribute::create([
            'name' => 'Description'
        ]);

        Attribute::create([
            'name' => 'Deadline'
        ]);

        Attribute::create([
           'name' => 'Image'
        ]);
    }
}
