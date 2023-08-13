<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Project::factory(3)->create();

        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            TaskSeeder::class,
        ]);
    }
}
