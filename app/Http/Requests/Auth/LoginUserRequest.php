<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
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
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ];
    }

    /**
     * Costume messages of errors Login
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'Vous dever saisir votre Email !',
            'email.email' => 'Votre Email est unvalide au niveau de la form, doit etre comme cet exemple <name@exemple.com>',
            'password.required' => 'Vous dever saisir votre Mot de Passe !',
        ];
    }
}
