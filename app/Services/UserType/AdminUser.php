<?php

namespace App\Services\UserType;

use App\Traits\ApiResponseTrait;

class AdminUser implements UserTypeInterface
{
    use ApiResponseTrait;
    public function getUserData($user)
    {
        return [
            'user' => $user,
        ];
    }
}
