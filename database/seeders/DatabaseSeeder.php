<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\Attribute\AttributeSeeder;
use Database\Seeders\Board\BoardTemplateSeeder;
use Database\Seeders\Organization\OrganizationSeeder;
use Database\Seeders\Permission\PermissionSeeder;
use Database\Seeders\Pivot\RolePermissionSeeder;
use Database\Seeders\Pivot\SubtasksAttributesSeeder;
use Database\Seeders\Pivot\UserOrganizationRoleSeeder;
use Database\Seeders\Pivot\UserProjectRoleSeeder;
use Database\Seeders\Project\ProjectSeeder;
use Database\Seeders\Role\RoleSeeder;
use Database\Seeders\Task\SubtaskTemplateSeeder;
use Database\Seeders\Task\TaskAttributeSeeder;
use Database\Seeders\Task\TaskTemplateSeeder;
use Database\Seeders\User\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       $this->call([
           RoleSeeder::class,
           PermissionSeeder::class,
           RolePermissionSeeder::class,
           UserSeeder::class,
           OrganizationSeeder::class,
           UserOrganizationRoleSeeder::class,
           ProjectSeeder::class,
           UserProjectRoleSeeder::class,
           BoardTemplateSeeder::class,
           TaskTemplateSeeder::class,
           SubtaskTemplateSeeder::class,
           AttributeSeeder::class,
           SubtasksAttributesSeeder::class
       ]);
    }
}
