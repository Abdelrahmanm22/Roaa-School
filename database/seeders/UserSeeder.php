<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'رفا أحمد',
                'email' => 'rafa@example.com',
                'password' => Hash::make('123456'),
                'role' => 'admin',
            ],
            [
                'name' => 'محمد رمضان',
                'email' => 'mohamed@example.com',
                'password' => Hash::make('123456'),
                'role' => 'parent',
                'passport_number'=>'65465454',
                'commission_number'=>'87987'
            ],
            [
                'name' => 'عبدالرحمن محمد رمضان',
                'email' => 'abdelrahman@example.com',
                'password' => Hash::make('123456'),
                'role' => 'student',
                'passport_number'=>'5626546545477',
                'commission_number'=>'68798799'
            ],
            [
                'name' => 'خالد محمد رمضان',
                'email' => 'khaled@example.com',
                'password' => Hash::make('123456'),
                'role' => 'student',
                'passport_number'=>'5626546545477999',
                'commission_number'=>'6879879879'
            ],
            [
                'name' => 'كريم محمد رمضان',
                'email' => 'kareem@example.com',
                'password' => Hash::make('123456'),
                'role' => 'student',
                'passport_number'=>'926546545477',
                'commission_number'=>'368798799'
            ],
            [
                'name' => 'أشرف عزب',
                'email' => 'Ashraf@example.com',
                'password' => Hash::make('123456'),
                'role' => 'parent',
                'passport_number'=>'26546545477',
                'commission_number'=>'68798799'
            ],
            [
                'name' => 'زياد أشرف عزب',
                'email' => 'zyiad@example.com',
                'password' => Hash::make('123456'),
                'role' => 'student',
                'passport_number'=>'2654654547755',
                'commission_number'=>'6879879932'
            ],
            [
                'name' => 'اياد أشرف عزب',
                'email' => 'Eyad@example.com',
                'password' => Hash::make('123456'),
                'role' => 'student',
                'passport_number'=>'26546547755',
                'commission_number'=>'68879932'
            ],

        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
