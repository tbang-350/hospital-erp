<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'permissions' => [
                    'manage_users',
                    'manage_roles',
                    'manage_employees',
                    'manage_patients',
                    'manage_appointments',
                    'manage_inventory',
                    'manage_suppliers',
                    'manage_invoices',
                    'manage_payroll',
                    'manage_assets',
                    'view_reports',
                    'manage_system_settings'
                ]
            ],
            [
                'name' => 'doctor',
                'display_name' => 'Doctor',
                'permissions' => [
                    'view_patients',
                    'manage_patients',
                    'view_appointments',
                    'manage_appointments',
                    'view_inventory',
                    'create_invoices',
                    'view_reports'
                ]
            ],
            [
                'name' => 'nurse',
                'display_name' => 'Nurse',
                'permissions' => [
                    'view_patients',
                    'manage_patients',
                    'view_appointments',
                    'manage_appointments',
                    'view_inventory',
                    'manage_inventory'
                ]
            ],
            [
                'name' => 'receptionist',
                'display_name' => 'Receptionist',
                'permissions' => [
                    'view_patients',
                    'manage_patients',
                    'view_appointments',
                    'manage_appointments',
                    'create_invoices',
                    'view_invoices'
                ]
            ],
            [
                'name' => 'pharmacist',
                'display_name' => 'Pharmacist',
                'permissions' => [
                    'view_patients',
                    'view_inventory',
                    'manage_inventory',
                    'view_suppliers',
                    'manage_suppliers'
                ]
            ],
            [
                'name' => 'accountant',
                'display_name' => 'Accountant',
                'permissions' => [
                    'view_invoices',
                    'manage_invoices',
                    'view_payroll',
                    'manage_payroll',
                    'view_reports',
                    'view_suppliers'
                ]
            ]
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }
    }
}
