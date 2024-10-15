<?php

namespace App\Http\Requests;

use App\Traits\ApiResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
class AddGuardianWithStudentsRequest extends FormRequest
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
            'guardian.name' => 'required|string|between:3,255',
            'guardian.email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'guardian.password' => 'required|string|confirmed|min:6',
            'guardian.passport_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'passport_number'),
            ],
            'guardian.commission_number' => 'nullable|string|max:50',
            'guardian.phone' => 'nullable|string|max:15',
            'guardian.whatsapp' => 'nullable|string|max:15',
            'guardian.family_members' => 'required|string|max:255',
            'guardian.city' => 'nullable|string|max:50',
            'guardian.district' => 'nullable|string|max:100',
            'guardian.building_number' => 'nullable|string|max:20',
            'guardian.apartment_number' => 'nullable|string|max:20',
            'students' => 'array',
            'students.*.name' => 'required|string|between:3,255',
            'students.*.passport_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'passport_number'),
            ],
            'students.*.commission_number' => 'nullable|string|max:50',
            'students.*.SSN' => 'nullable|string|max:50',
            'students.*.birthdate' => 'required|date',
            'students.*.gender' => 'required|string|in:ذكر,أنثي',
            'students.*.relationship' => 'nullable|string',
            'students.*.phone' => 'nullable|string|max:15',
            'students.*.rank' => 'nullable|integer|max:15',
            'students.*.current_grade_id' => 'required|exists:grades,id',
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
            'guardian.name.required' => 'اسم ولي الأمر مطلوب.',
            'guardian.name.between' => 'يجب أن يكون اسم ولي الأمر بين 3 و 255 حرفاً.',
            'guardian.email.required' => 'البريد الإلكتروني لولي الأمر مطلوب.',
            'guardian.email.email' => 'يجب أن يكون البريد الإلكتروني صالحاً.',
            'guardian.email.max' => 'يجب ألا يتجاوز البريد الإلكتروني 255 حرفاً.',
            'guardian.email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',
            'guardian.password.required' => 'كلمة المرور مطلوبة.',
            'guardian.password.confirmed' => 'يجب تأكيد كلمة المرور.',
            'guardian.password.min' => 'يجب أن تكون كلمة المرور على الأقل 6 أحرف.',
            'guardian.passport_number.required' => 'رقم جواز السفر مطلوب.',
            'guardian.passport_number.max' => 'يجب ألا يتجاوز رقم جواز السفر 50 حرفاً.',
            'guardian.passport_number.unique' => 'رقم جواز السفر مستخدم بالفعل.',
            'guardian.family_members.required' => 'عدد أفراد الأسرة مطلوب.',
            'students.*.name.required' => 'اسم الطالب مطلوب.',
            'students.*.name.between' => 'يجب أن يكون اسم الطالب بين 3 و 255 حرفاً.',
            'students.*.passport_number.required' => 'رقم جواز سفر الطالب مطلوب.',
            'students.*.passport_number.max' => 'يجب ألا يتجاوز رقم جواز سفر الطالب 50 حرفاً.',
            'students.*.passport_number.unique' => 'رقم جواز سفر الطالب مستخدم بالفعل.',
            'students.*.birthdate.required' => 'تاريخ ميلاد الطالب مطلوب.',
            'students.*.birthdate.date' => 'يجب أن يكون تاريخ الميلاد صالحاً.',
            'students.*.gender.required' => 'الجنس مطلوب.',
            'students.*.gender.in' => 'يجب أن يكون الجنس إما "ذكر" أو "أنثى".',
            'students.*.current_grade_id.required' => 'الصف الحالي للطالب مطلوب.',
            'students.*.current_grade_id.exists' => 'الصف غير موجود في السجلات.',
        ];
    }
}
