<?php


namespace App\Services\Filters\Payments\Pipes;


use App\Services\Filters\IFilter;
use Illuminate\Database\Eloquent\Builder;

class RowsNumberPipe implements IFilter
{
   public function filter(Builder $builder, string $value): Builder
   {
      if (is_null($value) || empty($value) || !isset($value))
         return $builder;

      return $builder->take(intval($value));
   }

   public function mapping(): array
   {
      return [];
   }


}
