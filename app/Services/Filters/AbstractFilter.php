<?php


namespace App\Services\Filters;


use Illuminate\Database\Eloquent\Builder;

abstract class AbstractFilter
{
    abstract public function filter(Builder $builder, string $value): Builder;
}

