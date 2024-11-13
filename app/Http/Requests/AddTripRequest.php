<?php

namespace App\Http\Requests;

use App\Traits\ApiResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddTripRequest extends FormRequest
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
            'title' => 'required|max:50',
            'subtitle' => 'required|max:350',
            'date' => 'required|date_format:d-m-Y',
            'description' => 'nullable',
            'images' => 'array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'videos' => 'array',
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
            'title.required' => 'العنوان مطلوب.',
            'title.max' => 'يجب ألا يزيد العنوان عن 50 حرفًا.',
            'subtitle.required' => 'العنوان الفرعي مطلوب.',
            'subtitle.max' => 'يجب ألا يزيد العنوان الفرعي عن 350 حرفًا.',
            'date.required' => 'التاريخ مطلوب.',
            'date.date_format' => 'يجب أن يكون التاريخ بصيغة DD-MM-YYYY.',
            'images.*.image' => 'يجب أن تكون كل صورة بصيغة صحيحة.',
        ];
    }
}
