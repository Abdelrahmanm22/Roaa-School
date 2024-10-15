<?php

namespace App\Services\UserType;

use App\Traits\ApiResponseTrait;

class MediaUser implements UserTypeInterface
{
    use ApiResponseTrait;
    public function getUserData($user)
    {
        return [
            'user' => $user,
        ];
    }
}
