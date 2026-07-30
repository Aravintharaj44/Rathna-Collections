<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Image required on create, optional on update.
        $imageRule = $this->isMethod('post') ? ['required'] : ['nullable'];

        return [
            'type' => ['required', Rule::in(['slider', 'offer', 'category'])],
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'link' => ['nullable', 'string', 'max:255'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'image' => array_merge($imageRule, ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096']),
            'status' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
