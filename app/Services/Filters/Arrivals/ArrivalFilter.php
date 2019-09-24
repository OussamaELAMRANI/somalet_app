<?php


namespace App\Services\Filters\Arrivals;

use App\Services\Filters\FilterAbstract;

class ArrivalFilter extends FilterAbstract
{
   protected $filters = [
      'facture' => FactureFilter::class,
      'dossier' => DossierFilter::class,
      'types' => TypesFilter::class,
      'with' => RelationsFilter::class,
      'status' => StatusFilter::class,
   ];
}

