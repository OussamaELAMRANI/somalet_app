<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProductionOrder extends Model
{
   protected $fillable = ['date_cmd'];

   public function scopeLast(Builder $builder)
   {
      return $builder->orderBy('id', 'desc')->get();
   }

   /**
    * Haves a Pivot table {po_items}
    */
   public function productSizes()
   {
      return $this->belongsToMany(ProductSize::class, 'po_items',
         'cmd_id', 'ps_id')
         ->withPivot(['order_quantity', 'fabric_quantity']);
   }


   /**
    * Save All Order Production Items
    *
    * @param array $items
    * @return self
    */
   public function addInternalOrder(array $items): self
   {
      $orderItem = array_map(function ($itm) {

         return array_merge($itm, ['cmd_id' => $this->id]);

      }, $items);

      $this->productSizes()->attach($orderItem);

      return $this;
   }
}
