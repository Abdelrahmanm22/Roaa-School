<?php

namespace App\Http\Requests;

use App\Traits\ApiResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateVideoRequest extends FormRequest
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
            'name' => 'required|max:100',
            'link' => 'required|max:500',
            'subject_id' => 'required|integer|exists:subjects,id',
            'grade_id' => 'required|integer|exists:grades,id',
            'term_id' => 'required|integer|exists:terms,id',
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
            'name.required' => 'اسم الفيديو مطلوب.',
            'name.max' => 'يجب ألا يتجاوز الاسم 100 حرف.',
            'link.required' => 'رابط الفيديو مطلوب.',
            'link.max' => 'يجب ألا يتجاوز الرابط 500 حرف.',
            'subject_id.required' => 'معرّف المادة مطلوب.',
            'subject_id.integer' => 'يجب أن يكون معرّف المادة رقمًا صحيحًا.',
            'subject_id.exists' => 'المادة المختارة غير موجودة.',
            'grade_id.required' => 'معرّف الصف مطلوب.',
            'grade_id.integer' => 'يجب أن يكون معرّف الصف رقمًا صحيحًا.',
            'grade_id.exists' => 'الصف المختار غير موجود.',
            'term_id.required' => 'معرّف الفصل الدراسي مطلوب.',
            'term_id.integer' => 'يجب أن يكون معرّف الفصل الدراسي رقمًا صحيحًا.',
            'term_id.exists' => 'الفصل الدراسي المختار غير موجود.',
        ];
    }
}
