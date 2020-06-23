<?php


namespace App\Services\Filters\Orders;


use App\Services\Filters\IFilter;
use Illuminate\Database\Eloquent\Builder;

class RelationsFilter implements IFilter
{

   /**
    * @overrid
    * @param Builder $builder
    * @param string $value
    * @return Builder
    */
   public function filter(Builder $builder, string $value): Builder
   {
      $relation = explode(',', $value);
      $relations = $this->ResolveFilterValue($relation);
      if (is_null($relations))
         return $builder;
      return $builder->with($relations);
   }

   public function mapping(): array
   {
      return [
         'client' => 'client',
//            'unit' => 'unit',
//            'provider' => 'provider',
//            'category' => 'subcategory',
//            'arrivals' => 'arrivals',
         'products' => 'product',
//            'sizes' => 'sizes',
      ];
   }

   public function ResolveFilterValue($keys)
   {
      $res = array();
      foreach ($keys as $key) {
         $res[] = data_get($this->mapping(), $key);
      }
      return array_filter($res);
   }
}
