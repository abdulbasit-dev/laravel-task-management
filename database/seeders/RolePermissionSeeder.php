<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions  = [
            "user_view",
            "user_add",
            "user_edit",
            "user_delete",

            "task_view",
            "task_add",
            "task_edit",
            "task_delete",

            // "_add",
            // "_edit",
            // "_view",
            // "_delete",
            // "_export",
            // "_import",
        ];

        //create permission
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
            ]);
        }

        $roles = ["product_owner", "developer", "tester"];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
            ]);
        }
    }
}
