<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['nullable', 'exists:project_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'string', 'max:50'],
            'client_name' => ['nullable', 'string', 'max:255'],
            'land_area' => ['nullable', 'string', 'max:100'],
            'building_area' => ['nullable', 'string', 'max:100'],
            'thumbnail' => ['nullable'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'max:50'],
            'is_featured' => ['nullable', 'boolean'],
        ];
    }
}
