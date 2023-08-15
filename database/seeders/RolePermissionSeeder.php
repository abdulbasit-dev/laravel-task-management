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
            "view_role",
            "add_role",
            "edit_role",
            "delete_role",

            "view_user",
            "add_user",
            "edit_user",
            "delete_user",

            "view_project",
            "add_project",
            "edit_project",
            "delete_project",

            "view_task",
            "add_task",
            "edit_task",
            "delete_task",

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

        // assign all permission to product owner
        $productOwner = Role::findByName("product_owner");
        $productOwner->givePermissionTo($permissions);
    }
}
