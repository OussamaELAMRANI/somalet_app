<?php


namespace App\Services\Filters\Payments\Pipes;


use App\Services\Filters\IFilter;
use Illuminate\Database\Eloquent\Builder;

class ValidPipe implements IFilter
{
   public function filter(Builder $builder, string $value): Builder
   {
      $validation = $this->ResolveFilterValue($value);
      if (is_null($validation))
         return $builder;
      return $builder->where('valid', intval($validation));
   }

   public function mapping(): array
   {
      return [
         'true' => '1',
         'false' => '0',
      ];
   }

   public function ResolveFilterValue($key)
   {
      $res[] = data_get($this->mapping(), $key);

      return $res[0];
   }

}
