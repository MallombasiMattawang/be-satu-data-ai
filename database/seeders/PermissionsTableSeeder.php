<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'roles' => ['index', 'create', 'edit', 'delete'],
            'permissions' => ['index', 'create', 'edit', 'delete'],
            'users' => ['index', 'create', 'edit', 'delete'],
            'packages' => ['index', 'create', 'edit', 'delete'],
            'promotions' => ['index', 'create', 'edit', 'delete'],
            'about_us' => ['index', 'create', 'edit', 'delete'],
            'blogs' => ['index', 'create', 'edit', 'delete'],
            'coverage_areas' => ['index', 'create', 'edit', 'delete'],
            'partners' => ['index', 'create', 'edit', 'delete'],
            'subscriptions' => ['index', 'create', 'edit', 'delete'],
            'testimonials' => ['index', 'create', 'edit', 'delete'],
        ];

        foreach ($permissions as $module => $actions) {
            foreach ($actions as $action) {
                Permission::create([
                    'name' => "{$module}.{$action}",
                    'guard_name' => 'api'
                ]);
            }
        }
    }
}
