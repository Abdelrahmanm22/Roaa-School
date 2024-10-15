<?php

namespace App\Http\Requests;

use App\Traits\ApiResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

//Request to Add a Student Outside TheSchool
class AddStudentRequest extends FormRequest
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
        return [
            'name' => 'required|string|between:3,255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'passport_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'passport_number'),
            ],
            'commission_number' => 'nullable|string|max:50',
            'SSN' => 'nullable|string|max:50',
            'birthdate' => 'required|date',
            'gender' => 'required|string|in:ذكر,أنثي',
            'relationship' => 'nullable|string',
            'phone' => 'nullable|string|max:15',
            'rank' => 'nullable|integer|max:15',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب.',
            'name.string' => 'الاسم يجب أن يكون نصًا.',
            'name.between' => 'يجب أن يتراوح طول الاسم بين 3 و 255 حرفًا.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.string' => 'البريد الإلكتروني يجب أن يكون نصًا.',
            'email.email' => 'يجب أن يكون البريد الإلكتروني عنوان بريد صالح.',
            'email.max' => 'يجب ألا يزيد طول البريد الإلكتروني عن 255 حرفًا.',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل.',
            'passport_number.required' => 'رقم جواز السفر مطلوب.',
            'passport_number.string' => 'رقم جواز السفر يجب أن يكون نصًا.',
            'passport_number.max' => 'يجب ألا يزيد طول رقم جواز السفر عن 50 حرفًا.',
            'passport_number.unique' => 'رقم جواز السفر مستخدم بالفعل.',
            'commission_number.string' => 'رقم التكليف يجب أن يكون نصًا.',
            'commission_number.max' => 'يجب ألا يزيد طول رقم التكليف عن 50 حرفًا.',
            'SSN.string' => 'رقم الهوية يجب أن يكون نصًا.',
            'SSN.max' => 'يجب ألا يزيد طول رقم الهوية عن 50 حرفًا.',
            'birthdate.required' => 'تاريخ الميلاد مطلوب.',
            'birthdate.date' => 'يجب أن يكون تاريخ الميلاد تاريخًا صالحًا.',
            'gender.required' => 'الجنس مطلوب.',
            'gender.string' => 'الجنس يجب أن يكون نصًا.',
            'gender.in' => 'الجنس يجب أن يكون "ذكر" أو "أنثي".',
            'relationship.string' => 'العلاقة يجب أن تكون نصًا.',
            'phone.string' => 'رقم الهاتف يجب أن يكون نصًا.',
            'phone.max' => 'يجب ألا يزيد طول رقم الهاتف عن 15 حرفًا.',
            'rank.integer' => 'الرتبة يجب أن تكون رقمًا صحيحًا.',
            'rank.max' => 'يجب ألا تزيد الرتبة عن 15.',
            'current_grade_id.exists' => 'المعرفة الحالية غير صحيحة.',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->apiResponse(null, $validator->errors(), 400)
        );
    }
}
