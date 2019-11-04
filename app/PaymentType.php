<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
   protected $guarded = [];

   public function payments()
   {
      return $this->hasMany(Payment::class, 'typed');
   }
}
