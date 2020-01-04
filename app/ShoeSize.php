<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShoeSize extends Model
{
   protected $fillable = ['size'];

   public function products()
   {
      return $this->belongsToMany(Product::class, 'product_size',
         'size_id', 'product_id')
         ->withPivot('weight');
   }
}
