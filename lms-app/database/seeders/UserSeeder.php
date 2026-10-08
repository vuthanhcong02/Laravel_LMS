<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed sample user accounts for 4 roles (Admin, Teacher, Student, Guest).
     */
    public function run(): void
    {
        // 1. Admin account
        User::updateOrCreate(
            ['email' => 'admin@lms.com'],
            [
                'first_name' => 'Quản Trị',
                'last_name' => 'Viên',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'avatar' => null,
                'exp_total' => 99999,
                'current_streak' => 30,
                'longest_streak' => 30,
                'last_learning_date' => now()->toDateString(),
            ]
        );

        // 2. Teacher account
        User::updateOrCreate(
            ['email' => 'teacher@lms.com'],
            [
                'first_name' => 'Nguyễn Thị',
                'last_name' => 'Giáo Viên',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => User::ROLE_TEACHER,
                'avatar' => null,
                'exp_total' => 15000,
                'current_streak' => 15,
                'longest_streak' => 20,
                'last_learning_date' => now()->toDateString(),
            ]
        );

        // 3. Student account
        User::updateOrCreate(
            ['email' => 'student@lms.com'],
            [
                'first_name' => 'Trần Văn',
                'last_name' => 'Học Viên',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => User::ROLE_STUDENT,
                'avatar' => null,
                'exp_total' => 1250,
                'current_streak' => 7,
                'longest_streak' => 14,
                'last_learning_date' => now()->toDateString(),
            ]
        );

        // 4. Guest account
        User::updateOrCreate(
            ['email' => 'guest@lms.com'],
            [
                'first_name' => 'Lê Hoàng',
                'last_name' => 'Khách',
                'email_verified_at' => null,
                'password' => Hash::make('password'),
                'role' => User::ROLE_GUEST,
                'avatar' => null,
                'exp_total' => 0,
                'current_streak' => 0,
                'longest_streak' => 0,
                'last_learning_date' => null,
            ]
        );

        // Seed additional sample students for pagination & leaderboard tests
        $extraStudents = [
            [
                'email' => 'student1@lms.com',
                'first_name' => 'Lê Thị',
                'last_name' => 'Mai',
                'role' => User::ROLE_STUDENT,
                'exp_total' => 2400,
                'current_streak' => 12,
            ],
            [
                'email' => 'student2@lms.com',
                'first_name' => 'Phạm Quốc',
                'last_name' => 'Bảo',
                'role' => User::ROLE_STUDENT,
                'exp_total' => 850,
                'current_streak' => 4,
            ],
            [
                'email' => 'student3@lms.com',
                'first_name' => 'Hoàng Minh',
                'last_name' => 'Đức',
                'role' => User::ROLE_STUDENT,
                'exp_total' => 3100,
                'current_streak' => 19,
            ],
        ];

        foreach ($extraStudents as $studentData) {
            User::updateOrCreate(
                ['email' => $studentData['email']],
                [
                    'first_name' => $studentData['first_name'],
                    'last_name' => $studentData['last_name'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'role' => $studentData['role'],
                    'avatar' => null,
                    'exp_total' => $studentData['exp_total'],
                    'current_streak' => $studentData['current_streak'],
                    'longest_streak' => $studentData['current_streak'] + 5,
                    'last_learning_date' => now()->toDateString(),
                ]
            );
        }
    }
}
