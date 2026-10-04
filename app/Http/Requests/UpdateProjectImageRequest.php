<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => ['sometimes', 'required', 'exists:projects,id'],
            'image' => ['nullable'],
            'caption' => ['nullable', 'string', 'max:255'],
        ];
    }
}
