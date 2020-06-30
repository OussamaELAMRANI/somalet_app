<?php


namespace App\Services\Filters\Payments\Pipes;


use App\Services\Filters\IFilter;
use Illuminate\Database\Eloquent\Builder;

class TypesPipe implements IFilter
{
   public function filter(Builder $builder, string $value): Builder
   {
      $types = (explode(',', $value));
      if (is_null($types) || empty($types))
         return $builder;
      return $builder->whereHas('types', function ($q) use ($types) {
         $q->whereIn('type', $types);
      });
   }

   public function mapping(): array
   {
      return [];
   }


}
