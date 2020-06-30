<?php

namespace App;

use App\Services\Filters\Payments\PaymentsFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Payment extends Model
{
   protected $guarded = [];
   use SoftDeletes;

   public function scopeFilter(Builder $builder, Request $req, array $filters = [])
   {
      return (new PaymentsFilter($req))->add($filters)->filter($builder);
   }

   public function client()
   {
      return $this->belongsTo(Client::class, 'client_id');
   }

   public function types()
   {
      return $this->belongsTo(PaymentType::class, 'typed');
   }

   public function formBank()
   {
      return $this->belongsTo(Bank::class, 'from_bank');
   }

   public function toBank()
   {
      return $this->belongsTo(Bank::class, 'in_bank');
   }
}
