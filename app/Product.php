<?php

namespace App;

use App\Services\Filters\Products\ProductFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Product extends Model
{
   use SoftDeletes;

   protected $guarded = [];

   public function scopeFilter(Builder $builder, Request $req, array $filters = [])
   {
      return (new ProductFilter($req))->add($filters)->filter($builder);
   }

   function unit()
   {
      return $this->belongsTo(Unit::class);
   }

   function color()
   {
      return $this->belongsTo(Color::class);
   }

   function provider()
   {
      return $this->belongsTo(Provider::class);
   }

   function subcategory()
   {
      return $this->belongsTo(SubCategory::class);
   }

   function user()
   {
      return $this->belongsTo(User::class);
   }

   function clients()
   {
      return $this->belongsToMany(Client::class, Discount::class)->withPivot(['discount']);
   }

   function pointures()
   {
      return $this->hasMany(Pointure::class, 'product_id');
   }
//    function larrival()
//    {
//        return $this->hasMany(LArrival::class, 'product_id');
//    }

   /**
    * Haves a Pivot table Order_items
    * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
   public function arrivals()
   {
      return $this->belongsToMany(Arrival::class, 'l_arrivals')
         ->withPivot(['qte_facture', 'rapport_qte', 'fret_douane', 'price_unit_ht', 'price_unit_ttc',
            'sell_price', 'price_devise', 'remark', 'date_reception', 'qte_reception', 'remark_reception']);
   }

   public function orders()
   {
      return $this->belongsToMany(Order::class, 'order_items',
         'product_id', 'cmd_id')
         ->withPivot(['qte', 'qte_price']);
   }
}
