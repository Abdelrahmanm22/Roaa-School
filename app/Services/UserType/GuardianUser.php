<?php

namespace App\Services\UserType;

use App\Models\Guardian;
use App\Traits\ApiResponseTrait;

class GuardianUser implements UserTypeInterface
{
    use ApiResponseTrait;
    public function getUserData($user)
    {
        $guardian = Guardian::where('user_id', $user->id)->with(['address'])->first();
        return [
            'user' => $user,
            'guardian_info' => $guardian,
//            'address' => $guardian->address,
        ];
    }
}
