<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions
        $permissions = [
            'manage_users',
            'manage_employees',
            'manage_patients',
            'manage_appointments',
            'manage_inventory',
            'manage_suppliers',
            'view_reports',
            'manage_roles',
            'manage_permissions'
        ];

        // Create roles with permissions
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'permissions' => $permissions // All permissions
            ],
            [
                'name' => 'doctor',
                'display_name' => 'Doctor',
                'permissions' => ['manage_patients', 'manage_appointments', 'view_reports']
            ],
            [
                'name' => 'nurse',
                'display_name' => 'Nurse',
                'permissions' => ['manage_patients', 'manage_appointments']
            ],
            [
                'name' => 'receptionist',
                'display_name' => 'Receptionist',
                'permissions' => ['manage_patients', 'manage_appointments']
            ],
            [
                'name' => 'pharmacist',
                'display_name' => 'Pharmacist',
                'permissions' => ['manage_inventory', 'manage_suppliers']
            ],
            [
                'name' => 'hr_manager',
                'display_name' => 'HR Manager',
                'permissions' => ['manage_employees', 'manage_users', 'view_reports']
            ]
        ];

        // Create roles
        foreach ($roles as $roleData) {
            $role = Role::firstOrCreate(
                ['name' => $roleData['name']],
                [
                    'display_name' => $roleData['display_name'],
                    'permissions' => $roleData['permissions']
                ]
            );
        }

        // Create default admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@hospital.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now()
            ]
        );

        // Assign admin role to the admin user
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole && !$adminUser->roles->contains($adminRole->id)) {
            $adminUser->roles()->attach($adminRole->id);
        }

        $this->command->info('Roles, permissions, and admin user created successfully!');
    }
}
