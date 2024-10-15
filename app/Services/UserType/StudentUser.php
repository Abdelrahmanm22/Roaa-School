<?php

namespace App\Services\UserType;

use App\Models\Student;
use App\Traits\ApiResponseTrait;

class StudentUser implements UserTypeInterface
{
    use ApiResponseTrait;
    public function getUserData($user)
    {
        $student = Student::where('user_id', $user->id)
            ->with(['guardian', 'currentGrade', 'currentTerm'])
            ->first();
        return [
            'user' => $user,
            'student_info' => $student,

        ];
    }
}
