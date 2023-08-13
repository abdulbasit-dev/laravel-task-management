<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();

        $faker = Factory::create();

        foreach (range(1, 10) as $index) {

            if($index == 1){
                $name = "basit";
                $user = User::firstOrCreate(
                    [
                        "email" => strtolower($name) . "@" . strtolower(str_replace(' ', '', config("app.name"))) . ".com",
                    ],
                    [
                        "name" => $name,
                        "password" => bcrypt("password"),
                    ]
                );
                $user->assignRole("product_owner");
                continue;
            }


            $name = $faker->firstName();
            $user = User::firstOrCreate(
                [
                    "email" => strtolower($name) . "@" . strtolower(str_replace(' ', '', config("app.name"))) . ".com",
                ],
                [
                    "name" => $name,
                    "password" => bcrypt("password"),
                ]
            );

            // for the 1 and 2 user, assign the role of product owner
            // and for 3-7 assign the role of developer
            // and rest of the user assign the role of tester

            if ($index == 1 || $index == 2) {
                $user->assignRole("product_owner");
            } elseif ($index >= 3 && $index <= 6) {
                $user->assignRole("developer");
            } else {
                $user->assignRole("tester");
            }
        }
    }
}
