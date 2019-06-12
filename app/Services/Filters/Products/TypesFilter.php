<?php


namespace App\Services\Filters\Products;


use App\Services\Filters\IFilter;
use Illuminate\Database\Eloquent\Builder;

class TypesFilter implements IFilter
{

    public function filter(Builder $builder, string $value): Builder
    {
        $types = $this->ResolveFilterValue([$value]);

        if (is_null($types))
            return $builder;
        return $builder->whereIn('type', $types);
    }

    public function mapping(): array
    {
        return [
            'premier' => 'MATIERE_PREMIERE',
            'fin' => 'PRODUIT_FINI',
            'all' => ['MATIERE_PREMIERE', 'PRODUIT_FINI'],
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
