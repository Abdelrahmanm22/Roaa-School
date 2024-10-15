<?php

namespace App\Http\Requests;

use App\Traits\ApiResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddResultsRequest extends FormRequest
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
            'student_id' => 'required|exists:students,user_id',
            'results' => 'required|array',
            'results.*' => 'numeric|min:0|max:50', // Adjust as needed
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
            'student_id.required' => 'رقم هوية الطالب مطلوب.',
            'student_id.exists' => 'الطالب غير موجود في السجلات.',
            'results.required' => 'النتائج مطلوبة.',
            'results.array' => 'يجب أن تكون النتائج في شكل مصفوفة.',
            'results.*.numeric' => 'يجب أن تكون كل نتيجة رقمية.',
            'results.*.min' => 'يجب ألا تكون النتيجة أقل من 0.',
            'results.*.max' => 'يجب ألا تكون النتيجة أكبر من 50.',
        ];
    }
}
