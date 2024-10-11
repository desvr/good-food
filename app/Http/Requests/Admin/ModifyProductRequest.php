<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ModifyProductRequest extends FormRequest
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
            'active' => 'required|integer',
            'name' => 'required|string|max:127',
            'slug' => 'nullable|string|max:127',
            'categories' => '',
            'description' => 'max:512',
            'weight' => 'nullable|integer',
            'calories' => 'nullable|numeric|min:0|max:999999.99',
            'price' => 'required|integer',
            'label' => 'nullable|string|max:16',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
