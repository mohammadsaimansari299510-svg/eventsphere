<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        User::updateOrCreate(
            ['email' => 'admin@eventsphere.edu'],
            [
                'name' => 'System Administrator',
                'username' => 'admin',
                'password' => Hash::make('password123'),
                'phone' => '+1234567890',
                'role' => 'admin',
                'department' => 'Administration',
                'enrolment_number' => 'ADM-2026-01',
                'status' => 'active',
            ]
        );

        // 2. Sample Organizer User
        User::updateOrCreate(
            ['email' => 'organizer@eventsphere.edu'],
            [
                'name' => 'Prof. Sarah Jenkins',
                'username' => 'organizer',
                'password' => Hash::make('password123'),
                'phone' => '+1987654321',
                'role' => 'organizer',
                'department' => 'Computer Science & Engineering',
                'enrolment_number' => 'FAC-CS-0042',
                'status' => 'active',
            ]
        );

        // 3. Sample Registered Student User
        User::updateOrCreate(
            ['email' => 'student@eventsphere.edu'],
            [
                'name' => 'Alex Rivera',
                'username' => 'alexstudent',
                'password' => Hash::make('password123'),
                'phone' => '+1555019283',
                'role' => 'student',
                'department' => 'Information Technology',
                'enrolment_number' => 'EN20269981',
                'status' => 'active',
            ]
        );
    }
}
