<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrayPermissions = [
            'permissionsCategories',
            'permissionsProduits',
            'permissionsUser',
            'permissionsRole',
            'permissionsEmployes',
            'permissionsCommandes',

        ];
        foreach ($arrayPermissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'employs']);
        }

        //
    }
}
