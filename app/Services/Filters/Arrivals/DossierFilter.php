<?php


namespace App\Services\Filters\Arrivals;


use Illuminate\Database\Eloquent\Builder;

class DossierFilter
{
    public function filter(Builder $builder, string $value)
    {
        return $builder->where('n_dossier', 'LIKE', "{$value}%");
    }

}
