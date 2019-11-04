<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
   protected $guarded = [];

   public function client()
   {
      return $this->belongsTo(Client::class,'client_id');
   }

   public function types()
   {
      return $this->belongsTo(PaymentType::class,'type');
   }

   public function formBanque()
   {
      return $this->belongsTo(Bank::class,'from_bank');
   }

   public function toBank()
   {
      return $this->belongsTo(Bank::class,'in_bank');
   }
}
