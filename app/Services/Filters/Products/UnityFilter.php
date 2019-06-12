<?php


namespace App\Services\Filters\Products;


use Illuminate\Database\Eloquent\Builder;

class UnityFilter
{
    public function filter(Builder $builder, string $value): Builder
    {
//        dd($value);
        return $builder->join('units AS u', 'unit_id', '=', 'u.id')
            ->where('u.name', 'LIKE', "%{$value}%");
    }

}
