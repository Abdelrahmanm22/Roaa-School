<?php

namespace App\Services;

use App\Models\User;

class UsersService
{
    public function createUser($data,$role)
    {
        return User::create([
            'name' => $data['name'],
            'email'=> $role ==='student' ? null : $data['email'],
            'password' => $role === 'student'
                ? bcrypt('123456')
                : bcrypt($data['password']),
            'passport_number'=>$data['passport_number'],
            'commission_number'=>$data['commission_number'],
            'role' => $role,
        ]);
    }
}
