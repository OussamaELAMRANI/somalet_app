<?php


namespace App\Services\Filters\Providers;


use App\Services\Filters\IFilter;
use Illuminate\Database\Eloquent\Builder;

class SearchFilter implements IFilter
{
    public function filter(Builder $builder, string $by): Builder
    {
        $searchValue = request('searchValue');
        $searchBy = $this->ResolveFilterValue($by);


        if (is_null($searchBy))
            $searchBy = 'steName';

        if (is_null($searchValue))
            return $builder;


        return $builder->where("{$searchBy}", 'LIKE', "%{$searchValue}%");
    }

    public function mapping(): array
    {
        return [
            'ste' => 'steName',
            'name' => 'firstName',
        ];
    }

    public function ResolveFilterValue($key)
    {
        return data_get($this->mapping(), $key);
    }
}
