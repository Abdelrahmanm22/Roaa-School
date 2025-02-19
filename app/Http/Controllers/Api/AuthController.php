<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\LoginRequest;
use App\Services\UserType\UserTypeFactory;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponseTrait;
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(LoginRequest  $request)
    {
        // Check if it's a student login (determine by the presence of 'code' field)
        if ($request->has('code')) {
            // Find the student by code (students don't need an email)
            $user = User::where('role', 'student')
                ->join('students', 'users.id', '=', 'students.user_id')
                ->where('students.code', $request->code)
                ->first();

            if ($user && auth()->attempt(['id' => $user->user_id, 'password' => $request->password])) {
                // Create a JWT token for the student
                $token = auth()->tokenById($user->user_id);
                return $this->createNewToken($token);
            }

            return $this->apiResponse(null, 'Unauthorized', 401);

        } else {

            if (!$token = auth()->attempt($request->validated())) {
                return $this->apiResponse(null, 'Unauthorized', 401);
            }
            return $this->createNewToken($token);
        }
    }


    public function logout() {
        auth()->logout();
        return $this->apiResponse(null,'User successfully signed out',200);
    }

    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    public function userProfile(Request $request) {
        $user = auth()->user();

        // Use UserTypeFactory to get user-specific data
        $userType = UserTypeFactory::make($user);
        $userData = $userType->getUserData($user);

        return $this->apiResponse($userData, 'User profile retrieved successfully', 200);
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => auth()->user()
        ]);
    }

    public function updatePassword(Request $request)
    {
        
        $user = auth()->user();
        
        // Validate request
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors()->first(), 400);
        }



        // Check if the current password is correct
        if (!\Hash::check($request->current_password, $user->password)) {
            return $this->apiResponse(null, 'Current password is incorrect', 401);
        }

        // Update password
        $user->update(['password' => bcrypt($request->new_password)]);

        return $this->apiResponse(null, 'Password updated successfully', 200);
    }

}
