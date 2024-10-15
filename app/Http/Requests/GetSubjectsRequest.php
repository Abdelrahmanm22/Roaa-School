<?php

namespace App\Http\Requests;

use App\Traits\ApiResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetSubjectsRequest extends FormRequest
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
            'grade_id' => 'required|integer|exists:grades,id',
            'term_id' => 'required|integer|in:1,2', // Ensure term_id is either 1 or 2
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
            'grade_id.required' => 'معرّف الصف مطلوب.',
            'grade_id.integer' => 'يجب أن يكون معرّف الصف رقمًا صحيحًا.',
            'grade_id.exists' => 'الصف المختار غير موجود.',
            'term_id.required' => 'معرّف الفصل الدراسي مطلوب.',
            'term_id.integer' => 'يجب أن يكون معرّف الفصل الدراسي رقمًا صحيحًا.',
            'term_id.in' => 'يجب أن يكون معرّف الفصل الدراسي إما 1 أو 2 فقط.',
        ];
    }
}
