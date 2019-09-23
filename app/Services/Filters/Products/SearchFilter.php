<?php


namespace App\Services\Filters\Products;


use App\Services\Filters\IFilter;
use Illuminate\Database\Eloquent\Builder;

class SearchFilter implements IFilter
{
   public function filter(Builder $builder, string $by): Builder
   {
      $searchValue = request('searchValue');
      $searchBy = $this->ResolveFilterValue($by);

      if (is_null($searchValue))
         return $builder;

      switch ($searchBy) {
         case null:
            $searchBy = 'reference';
            break;
         case 'provider':
            return $builder->join('providers AS p', 'provider_id', '=', 'p.id')
               ->where('p.steName', 'LIKE', "%{$searchValue}%");
         case  'color':
         case 'unit':
            return $builder->join("{$searchBy}s AS p", "{$searchBy}_id", '=', 'p.id')
               ->where('p.name', 'LIKE', "%{$searchValue}%");

//         case 'name':
//         case'reference':
//         default:
//            $searchBy = 'reference';
      }
      return $builder->where("products.{$searchBy}", 'LIKE', "%{$searchValue}%");

   }

   public function mapping(): array
   {
      return [
         'ref' => 'reference',
         'name' => 'name',
         'provider' => 'provider',
         'color' => 'color',
         'unit' => 'unit',
      ];
   }

   public function ResolveFilterValue($key)
   {
      return data_get($this->mapping(), $key);
   }
}
