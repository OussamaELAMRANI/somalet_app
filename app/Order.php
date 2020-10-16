<?php

namespace App;

use App\Services\Filters\Orders\OrdersFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Order extends Model
{
   use SoftDeletes;

   protected $guarded = [];

   public function scopeFilter(Builder $builder, Request $req, array $filters = [])
   {
      return (new OrdersFilter($req))->add($filters)->filter($builder);
   }

   public function scopeLast(Builder $builder)
   {
      return $builder->orderBy('id', 'desc')->get();
   }

   function client()
   {
      return $this->belongsTo(Client::class);
   }

   /**
    * Haves a Pivot table {order_items}
    */
   public function product()
   {
      return $this->belongsToMany(Product::class, 'order_items',
         'cmd_id', 'product_id')
         ->withPivot(['qte', 'qte_rapport', 'price', 'discount']);
   }

   /**
    * Haves a Pivot table {order_ps}
    */
   public function productSize()
   {
      return $this->belongsToMany(ProductSize::class, 'order_ps',
         'cmd_id', 'ps_id')
         ->withPivot(['qte', 'price', 'discount', 'is_replaced', 'is_replaceable']);
   }


   /**
    * Save All Order Items for this Client  {order_ps}
    *
    * @param array $items
    * @return Order
    */
   public function savePSItems(array $items)
   {
      foreach ($items as $item) {
         $orderItem = [];

         foreach ($item['qte'] as $q) {
            $orderItem[] = [
               'cmd_id' => $this->id,
               'ps_id' => $q['ps_id'],
               'price' => $item['sell'],
               'discount' => $item['discount'],
               'qte' => $q['qte'],
            ];
         }
         $this->productSize()->attach($orderItem);
      }

      return $this;
   }

   /**
    * Save All  Order for this Client  {order_items}
    *
    * @param array $items
    * @return Order
    */
   public function saveItems(array $items)
   {
      $orderItem = array_map(function ($itm) {
         return array_merge($itm, ['cmd_id' => $this->id]);
      }, $items);

      $this->product()->attach($orderItem);

      return $this;
   }


}
