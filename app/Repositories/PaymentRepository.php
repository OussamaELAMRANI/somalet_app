<?php

namespace App\Repositories;

use App\Payment;

/**
 * Manage Payment by direct access to Eloquent
 *
 * Class PaymentRepository
 * @package App\Repositories
 */
class PaymentRepository
{
   /**
    * @param int $month
    * @param int $year
    * @return Payment|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
    */
   private function getDeadline(int $month, int $year)
   {
      return Payment::with('client')
         ->join('payment_types AS t', 't.id', '=', 'payments.typed')
         ->whereIn('t.type', ['CHEQUE', 'EFFET'])
         ->whereYear('date_deadline', $year)
         ->whereMonth('date_deadline', $month)
         ->where('valid', 1)
         ->where('in_bank', null);
   }

   /**
    * Manage Deadline payments.
    *
    * @param int $month
    * @param int $year
    *
    * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
    */
   public function getByDeadline(int $month, int $year)
   {
      return $this->getDeadline($month, $year)
         ->select(['payments.*', 't.type'])
         ->get();
   }

   /**
    * @param int $month
    * @param int $year
    * @return int
    */
   public function deadlineCount(int $month, int $year)
   {
      return $this->getDeadline($month, $year)->count();
   }

}
