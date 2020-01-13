<?php

namespace App;

use App\Services\Filters\Arrivals\ArrivalFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Arrival extends Model
{
   use SoftDeletes;
   protected $guarded = [];

   public function scopeFilter(Builder $builder, Request $req, array $filters = [])
   {
      return (new ArrivalFilter($req))->add($filters)->filter($builder);
   }

   public function scopeLast(Builder $builder)
   {
      return $builder->orderBy('id', 'desc')->get();
   }

   function provider()
   {
      return $this->belongsTo(Provider::class);
   }

   function user()
   {
      return $this->belongsTo(User::class);
   }
//    function larrival()
//    {
//        return $this->hasMany(LArrival::class, 'arrival_id');
//    }


   public function getKeyName()
   {
      return $this->primaryKey;
   }


   /**
    * Haves a Pivot table l_arrivals
    * @return BelongsToMany
    */
   public function product()
   {
      return $this->belongsToMany(Product::class, 'l_arrivals')
         ->withPivot(['qte_facture', 'rapport_qte', 'price_unit_ht', 'sell_price', 'remark',
            'date_reception', 'qte_reception', 'qte_rapport_reception', 'remark_reception']);
   }

   /**
    * Save All Order Items for this Client
    *
    * @param array $items
    * @param int $arrival_id
    * @return Arrival Total
    */
   public function saveItems(array $items, $arrival_id = 0): self
   {
      $orderItem = array_map(function ($itm) {
         unset($itm['name']);
         return array_merge($itm, ['arrival_id' => $this->id]);
      }, $items);

      $this->product()->attach($orderItem);


      return $this;
   }
}
