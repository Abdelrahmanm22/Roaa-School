<?php

namespace App\Http\Requests;

use App\Traits\ApiResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateJobRequest extends FormRequest
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
            'name' => 'required|max:30',
            'email' => 'required|email',
            'message' => 'nullable|max:300',
            'phone' => 'required|max:15',
            'resume' => 'required|file|mimes:pdf,doc,docx,ppt,pptx|max:5120',
            'title' => 'required|max:20',
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
            'name.required' => 'اسم المتقدم مطلوب.',
            'name.max' => 'يجب ألا يتجاوز الاسم 30 حرفاً.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'يجب أن يكون البريد الإلكتروني صالحاً.',
            'message.max' => 'يجب ألا يتجاوز الرسالة 300 حرف.',
            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.max' => 'يجب ألا يتجاوز رقم الهاتف 15 رقماً.',
            'resume.required' => 'يجب تقديم السيرة الذاتية.',
            'resume.file' => 'يجب أن تكون السيرة الذاتية ملفاً.',
            'resume.mimes' => 'يجب أن تكون السيرة الذاتية بصيغة pdf أو doc أو docx أو ppt أو pptx.',
            'resume.max' => 'يجب ألا يتجاوز حجم السيرة الذاتية 5 ميجابايت.',
            'title.required' => 'عنوان الوظيفة مطلوب.',
            'title.max' => 'يجب ألا يتجاوز العنوان 20 حرفاً.',
        ];
    }
}
