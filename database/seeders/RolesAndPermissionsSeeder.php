<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create permission
        $editOwnData = Permission::create(['name' => 'edit own data']);
        $deleteOwnData = Permission::create(['name' => 'delete own data']);
        $createData = Permission::create(['name' => 'create data']);
        $editAnyData = Permission::create(['name' => 'edit any data']);
        $deleteAnyData = Permission::create(['name' => 'delete any data']);

        // Create role
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleAdmin->permissions()->attach([
            $editOwnData->id,
            $deleteOwnData->id,
            $createData->id,
            $editAnyData->id,
            $deleteAnyData->id,
        ]);

        $roleUser = Role::create(['name' => 'user']);
        $roleUser->permissions()->attach([
            $editOwnData->id,
            $deleteOwnData->id,
            $createData->id,
        ]);
    }
}