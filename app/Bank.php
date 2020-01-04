<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
   protected $guarded = [];

   function payments()
   {
      return $this->hasMany(Payment::class, 'in_bank');
   }

   function paymentsFrom()
   {
      return $this->hasMany(Payment::class, 'in_bank');
   }
}
