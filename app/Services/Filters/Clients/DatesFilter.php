<?php


namespace App\Services\Filters\Clients;


use App\Services\Filters\IFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class DatesFilter
{

   /**
    * @overrid
    * @param Builder $builder
    * @param string $value
    * @return Builder
    */
   public function filter(Builder $builder, string $value): Builder
   {
      $dates = explode(',', $value);
      $dates = $this->ResolveFilterValue($dates);

      $from = $dates[0];
      $to = $dates[1];

      if (is_null($dates))
         return $builder;
      if (is_null($to))
         $to = Carbon::today()->toDateString();


      return $builder->whereHas('commands', function ($q) use ($from, $to) {
          $q->whereBetween('date_cmd', [$from, $to]);
      });

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
