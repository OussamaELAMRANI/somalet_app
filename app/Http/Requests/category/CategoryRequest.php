<?php

namespace App\Http\Requests\category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'category_name' => 'required|string|unique:categories'
        ];
    }

    public function messages()
    {
        return [
            'category_name.required' => 'Vous douvez inserer le nom de la caterorie',
            'category_name.unique' => 'Cette caterorie a deja existe !!'
        ];
    }
}
