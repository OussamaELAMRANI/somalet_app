<?php


namespace App\Services\Filters\Arrivals;


use Illuminate\Database\Eloquent\Builder;

class FactureFilter
{
    public function filter(Builder $builder, string $value)
    {
        return $builder->where('n_facture', 'LIKE', "{$value}%");
    }

}
