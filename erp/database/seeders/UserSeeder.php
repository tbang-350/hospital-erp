<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'System Administrator',
                'email' => 'admin@hospital.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'admin'
            ],
            [
                'name' => 'Dr. John Smith',
                'email' => 'doctor@hospital.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'doctor'
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'nurse@hospital.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'nurse'
            ],
            [
                'name' => 'Mary Wilson',
                'email' => 'receptionist@hospital.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'receptionist'
            ],
            [
                'name' => 'David Brown',
                'email' => 'pharmacist@hospital.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'pharmacist'
            ],
            [
                'name' => 'Lisa Davis',
                'email' => 'accountant@hospital.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'accountant'
            ]
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);
            
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
            
            // Assign role to user
            $roleModel = Role::where('name', $role)->first();
            if ($roleModel && !$user->hasRole($role)) {
                $user->assignRole($roleModel);
            }
        }
    }
}
