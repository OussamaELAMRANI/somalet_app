<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'reference' => 'required|string|unique:products',
            'name' => 'required|string|unique:products',
            'color_id' => 'required',
            'unit_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'reference.required' => 'Vous devez saisir la reference',
            'reference.unique' => 'la reference entree existe deja dans la base de donnee',
            'name.required' => 'Vous devez saisir le nom',
            'color_id.required' => 'Vous devez selectionner une couleur',
            'unit_id.required' => 'Vous devez selectionner une unite de mesure',
        ];
    }
}
