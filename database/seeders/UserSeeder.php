<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => "denis",
            'email' => 'denis.le01@mail.ru',
            'password' => 'denis123'
        ]);

        User::create(
        [
            'name' => "ivan",
            'email' => 'ivan@mail.ru',
            'password' => 'ivan123'
        ]);

        User::create([
            'name' => "sergey",
            'email' => 'sergey@mail.ru',
            'password' => 'sergey123'
        ]);

        User::create([
            'name' => "noRoleUser",
            'email' => 'd61173897@gmail.com',
            'password' => 'norole123'
        ]);
    }
}
