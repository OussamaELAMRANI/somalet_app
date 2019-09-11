<?php

namespace App;

//use App\Services\Filters\Providers\ProviderFilter;
use App\Services\Filters\Clients\ClientFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Client extends Model
{
   use SoftDeletes;
   // this param means all the col in Provider table are fillable
   protected $guarded = [];

   public function scopeFilter(Builder $builder, Request $req, array $filters = [])
   {
      return (new ClientFilter($req))->add($filters)->filter($builder);
   }

   public function scopeDesc(Builder $builder)
   {
      return $builder->orderBy('id', 'desc');
   }

//    function commande()
//    {
//        return $this->hasMany(Commande::class, 'client_id');
//    }

   /**
    * Haves a Pivot table {order_items}
    */
   public function products()
   {
      return $this->belongsToMany(Product::class, 'discounts',
         'client_id', 'product_id')
         ->withPivot('discount');
   }

//
//    function factures()
//    {
//        return $this->hasMany(Facture::class, 'client_id');
//    }

}
