<?php


namespace App\Services\Filters\Arrivals;


use App\Services\Filters\IFilter;
use Illuminate\Database\Eloquent\Builder;

class StatusFilter implements IFilter
{
   public function filter(Builder $builder, string $value): Builder
   {
      $relation = explode(',', $value);
      $relations = $this->ResolveFilterValue($relation);
      if (is_null($relations))
         return $builder;
      return $builder->whereIn('state', $relations);
   }

   public function mapping(): array
   {
      return [
         'attent' => 'ATTENTE',
         'vue' => 'VUE',
         'reception' => 'RECEPTION',
         'valid' => 'VALID',
         'all' => ['ATTENTE', 'VUE', 'RECEPTION', 'VALID'],
      ];
   }

   public function ResolveFilterValue($keys)
   {
      $res = array();
      foreach ($keys as $key) {
         if ($key == 'all')
            $res = array_merge(data_get($this->mapping(), $key), $res);
         else
            $res[] = data_get($this->mapping(), $key);
      }
      return array_filter($res);
   }

}
