<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class CommandRequest extends FormRequest
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
         'cmd_number' => 'required|string|unique:orders',
         'date_cmd' => 'required|date',
      ];
   }

   public function messages()
   {
      $msg = 'de cette commande !';
      return [
         'cmd_number.required' => "Vous devez saisir le numéro {$msg}",
         'date_cmd.required' => "Vous devez saisir la date {$msg}",
      ];
   }
}
