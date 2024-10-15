<?php

namespace App\Services\UserType;

class UserTypeFactory
{
    public static function make($user)
    {
        switch ($user->role) {
            case 'admin':
                return new AdminUser();
            case 'parent':
                return new GuardianUser();
            case 'student':
                return new StudentUser();
            case 'data entry':
                return new DataEntryUser();
            case 'media':
                return new MediaUser();
            default:
                throw new \Exception("Unsupported user type");
        }
    }
}
