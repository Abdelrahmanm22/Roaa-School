<?php

namespace App\Services;

use App\Models\Student;
use App\Models\User;

class UsersService
{
    public function createUser($data,$role,$status=null)
    {
        // Check if the role is student
        if ($role === 'student') {
            // Check if the student's status is 'مقبول'
            $studentStatus = $status;
            $password = $studentStatus === 'مقبول' ? bcrypt($data['password']) : bcrypt('123456');
        } elseif ($role === 'parent') {
            // For parent role, set the password to bcrypt($data['password'])
            $password = bcrypt($data['password']);
        } else {
            // For other roles, set the default password to '123456'
            $password = bcrypt('123456');
        }

        return User::create([
            'name' => $data['name'],
            'email'=> $role ==='student' ? null : $data['email'],
            'password' => $password,
            'passport_number'=>$data['passport_number'],
            'commission_number'=>$data['commission_number'],
            'role' => $role,
        ]);
    }

    public function passportNumberExists($passportNumber)
    {
        return User::where('passport_number', $passportNumber)->exists();
    }

}
