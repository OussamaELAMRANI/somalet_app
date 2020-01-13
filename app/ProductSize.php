<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
   protected $table = 'product_size';

   public function productionOrders()
   {
      return $this->belongsToMany(ProductionOrder::class,
         'po_items', 'ps_id', 'cmd_id')
         ->withPivot(['order_quantity', 'fabric_quantity']);
   }

   public function commands()
   {
      return $this->belongsToMany(Order::class, 'order_ps',
         'ps_id', 'cmd_id')
         ->withPivot(['qte', 'price', 'discount', 'is_replaced', 'is_replaceable']);
   }

   public function product()
   {
      return $this->belongsTo(Product::class, 'product_id');
   }

   public function size()
   {
      return $this->belongsTo(ShoeSize::class, 'size_id');
   }


}
