<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SmsTemplateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'template_type' => 'nullable|min:2|max:255',
            'template_subject' => 'required',
            'custom_content' => 'nullable',
        ];
    }
}