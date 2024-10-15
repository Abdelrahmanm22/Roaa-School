<?php

namespace App\Services\UserType;

use App\Traits\ApiResponseTrait;

class DataEntryUser implements UserTypeInterface
{
    use ApiResponseTrait;
    public function getUserData($user)
    {
        return [
            'user' => $user,
        ];
    }
}
