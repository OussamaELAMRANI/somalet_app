<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SignUpClientRequest extends FormRequest
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
            'name' => 'required|string',
//            'type_user' => 'string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
        ];
    }

    /**
     * Here you can Costume Messages from Request
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Vous devez saisir votre nom complet !',
            'email.required' => 'Vous devez saisir votre Email <exempel@domain.com> !',
            'password.required' => 'Vous devez saisir le mot de passe !',
            'password.confirmed' => 'Les mots de passes ne sont pas correspondents !',
        ];
    }
}
