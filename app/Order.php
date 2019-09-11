<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
   protected $guarded = [];

   public function scopeLast(Builder $builder)
   {
      return $builder->orderBy('id', 'desc')->get();
   }

   function client()
   {
      return $this->belongsTo(Client::class);
   }

   function payments()
   {
      return $this->hasMany(Payment::class,'cmd_id');
   }


   /**
    * Haves a Pivot table {order_items}
    */
   public function product()
   {
      return $this->belongsToMany(Product::class, 'order_items',
         'cmd_id', 'product_id')
         ->withPivot(['qte', 'qte','discount']);
   }

   /**
    * Save All Order Items for this Client
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

      // Updating original Inventory TODO
//        foreach ($items as $prod) {
//            $upProduct = $this->product()->find($prod['productId']);
//            $upProduct->inventory -= $prod['quantity'];
//            $upProduct->save();
//        }
      return $this;
   }
}
