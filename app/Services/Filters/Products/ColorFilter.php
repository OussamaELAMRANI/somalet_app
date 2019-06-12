<?php


namespace App\Services\Filters\Products;


use Illuminate\Database\Eloquent\Builder;

class ColorFilter
{
    public function filter(Builder $builder, string $value): Builder
    {
//        dd($value);
        return $builder->join('colors AS c', 'color_id', '=', 'c.id')
            ->where('c.name', 'LIKE', "%{$value}%");
    }
}
