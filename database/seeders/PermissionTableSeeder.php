<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = \App\Constants\Permission::all();
//        Permission::query()->forceDelete();
        foreach ($permissions as $permission) {
            $updatedPermission = Permission::query()->updateOrCreate(['name' => $permission]);
            $description = str_replace("_", " ", $permission);
            $description = ucwords(strtolower($description));
            $updatedPermission->update([
                'description' => $description,
            ]);
        }
    }
}
