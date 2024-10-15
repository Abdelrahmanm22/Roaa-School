<?php

namespace App\Http\Requests;

use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class LoginRequest extends FormRequest
{
    use ApiResponseTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Check if it's a student login (determine by the presence of 'code' field)
        if ($this->has('code')) {
            return [
                'code' => 'required|string',
                'password' => 'required|string|min:6',
            ];
        }

        // Default login logic for non-student users (email login)
        return [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->apiResponse(null, $validator->errors(), 400)
        );
    }
    public function messages()
    {
        return [
            'code.required' => 'الرجاء ادخال رمز الطالب.',
            'code.string' => 'يجب ان يكون رمز الطالب نوع نص.',
            'password.required' => 'الرجاء ادخال الرقم السري.',
            'password.string' => 'يجب ان يكون الرقم السري نوعه نص.',
            'password.min' => 'يجب أن يحتوي حقل الرقم السري على 6 أحرف على الأقل.',
            'email.required' => 'الرجاء إدخال البريد الإلكتروني.',
            'email.email' => 'يجب أن يكون البريد الإلكتروني صالحاً.',
        ];
    }
}
