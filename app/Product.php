<?php

namespace App;

use App\Services\Filters\Products\ProductFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

/**
 * @method static insert(array $products)
 * @method static filter(Request $req)
 * @property mixed id
 */
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

//   function pointures()
//   {
//      return $this->hasMany(Pointure::class, 'product_id');
//   }


   /**
    * Haves a Pivot table Order_items
    * @return BelongsToMany
    */
   public function arrivals()
   {
      return $this->belongsToMany(Arrival::class, 'l_arrivals')
         ->withPivot(['qte_facture', 'rapport_qte', 'price_unit_ht', 'sell_price', 'remark',
            'date_reception', 'qte_reception', 'qte_rapport_reception', 'remark_reception']);
   }

   public function orders()
   {
      return $this->belongsToMany(Order::class, 'order_items',
         'product_id', 'cmd_id')
         ->withPivot(['qte', 'qte_rapport', 'discount', 'price']);
   }

   public function sizes()
   {
      return $this->belongsToMany(ShoeSize::class, 'product_size',
         'product_id', 'size_id')
         ->withPivot(['weight','id']);
   }

   public function saveSizes(array $sizes, $now): self
   {
      $dts = $now->toDateTimeString();
      $productSizes = array_map(function ($size) use ($dts) {
         if ($size['weight'] != '') {
            return [
               "size_id" => $size['id'],
               "product_id" => $this->id,
               "weight" => $size['weight'],
               'created_at' => $dts,
               'updated_at' => $dts
            ];
         }

      }, $sizes);
      $productSizes = array_filter($productSizes);

      $this->sizes()->attach($productSizes);

      return $this;
   }

}
