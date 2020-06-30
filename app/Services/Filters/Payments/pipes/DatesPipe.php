<?php


namespace App\Services\Filters\Payments\Pipes;


use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

class DatesPipe
{

   /**
    * @overrid
    * @param Builder $builder
    * @param string $value
    * @return Builder
    */
   public function filter(Builder $builder, string $value): Builder
   {
      $dates = $this->ResolveFilterValue(explode(',', $value));

      $from = $dates[0];
      $to = $dates[1];

      if (is_null($dates))
         return $builder;
      if (is_null($to))
         $to = Carbon::today()->toDateString();

      return $builder->whereBetween('payed_at', [$from, $to]);
   }


   public function ResolveFilterValue($keys)
   {
      $res = array();
      foreach ($keys as $key) {
         if ($this->validateDate($key)) {
            $d = Carbon::parse($key)->toDateString();
            $res[] = $d;
         }
      }
      return $res;
   }

   function validateDate($date, $format = 'Y-m-d')
   {
      $d = \DateTime::createFromFormat($format, $date);
      // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
      return $d && $d->format($format) === $date;
   }
}
