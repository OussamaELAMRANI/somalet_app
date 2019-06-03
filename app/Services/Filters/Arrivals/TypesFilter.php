<?php


namespace App\Services\Filters\Arrivals;


use Illuminate\Database\Eloquent\Builder;

class TypesFilter
{
    public function filter(Builder $builder, string $value): Builder
    {
        $relation = explode(',', $value);
        $relations = $this->ResolveFilterValue($relation);
        if (is_null($relations))
            return $builder;
        return $builder->whereIn('type', $relations);
    }

    public function mapping(): array
    {
        return [
            'international' => 'INTERNATIONAL',
            'national' => 'NATIONAL',
            'all' => ['NATIONAL', 'INTERNATIONAL'],
        ];
    }

    public function ResolveFilterValue($keys)
    {
        $res = array();
        foreach ($keys as $key) {
            if ($key == 'all')
                $res = array_merge(data_get($this->mapping(), $key), $res);
            else
                $res[] = data_get($this->mapping(), $key);
        }
        return array_filter($res);
    }

}
