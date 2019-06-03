<?php


namespace App\Services\Filters;


use Illuminate\Database\Eloquent\Builder;

interface IFilter
{
    public function filter(Builder $builder, string $value): Builder;
    public function mapping(): array;
}
