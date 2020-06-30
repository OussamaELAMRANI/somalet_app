<?php


namespace App\Services\Filters\Payments;

use App\Services\Filters\Payments\Pipes\{DatesPipe, OfPipe, RelationsPipe, RowsNumberPipe, TypesPipe, ValidPipe};
use App\Services\Filters\FilterAbstract;

class PaymentsFilter extends FilterAbstract
{
   protected $filters = [
      'between' => DatesPipe  ::class,
      'of' => OfPipe::class,
      'types' => TypesPipe::class,
      'with' => RelationsPipe::class,
      'valid' => ValidPipe::class,
      'take' => RowsNumberPipe::class,
   ];
}

