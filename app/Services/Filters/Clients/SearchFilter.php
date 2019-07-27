<?php


namespace App\Services\Filters\Clients;


use App\Services\Filters\IFilter;
use Illuminate\Database\Eloquent\Builder;

class SearchFilter implements IFilter
{
    public function filter(Builder $builder, string $by): Builder
    {
        $searchValue = request('searchValue');
        $searchBy = $this->ResolveFilterValue($by);


        if (is_null($searchBy))
            $searchBy = 'nom';

        if (is_null($searchValue))
            return $builder;


        return $builder->where("{$searchBy}", 'LIKE', "%{$searchValue}%");
    }

    public function mapping(): array
    {
        return [
            'nom' => 'nom',
        ];
    }

    public function ResolveFilterValue($key)
    {
        return data_get($this->mapping(), $key);
    }
}
