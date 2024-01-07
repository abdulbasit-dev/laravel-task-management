<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tasks')->delete();

        $faker = Factory::create();

        $project = Project::inRandomOrder()->first();
        foreach (range(1, 2) as $index) {
            Task::withoutEvents(function () use ($project, $faker) {
                Task::create([
                    "project_id" => $project->id,
                    "created_by" => User::role("product_owner")->inRandomOrder()->first()->id,
                    "title" => $faker->sentence(),
                    "description" => $faker->sentence(10),
                    "due_date" => $faker->dateTimeBetween(now(), "+3 days"),
                ]);
            });
        }
    }
}
