<?php

namespace App\Http\Requests\SubtaskTemplate;

use Illuminate\Foundation\Http\FormRequest;

class SubtaskTemplateRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'task_template_id' => 'required|integer|exists:task_templates,id'
        ];
    }
}
