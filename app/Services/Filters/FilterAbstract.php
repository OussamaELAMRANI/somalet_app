<?php


namespace App\Services\Filters;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class FilterAbstract
{
    protected $req;
    protected $filters = [];

    public function __construct(Request $request)
    {
        $this->req = $request;
    }

    public function filter(Builder $builder)
    {
        foreach ($this->getFilters() as $filter => $value) {
            ($this->resolveFilter($filter)->filter($builder, $value));
        }
//        die();
        return $builder;
    }

    public function add($customFilter)
    {
        $this->filters = array_merge($this->filters, $customFilter);
        return $this;
    }

    public function getFilters()
    {
        return $this->filterQuery($this->filters);
    }

    public function filterQuery($filter)
    {
        return array_filter($this->req->only(array_keys($filter)));
    }

    public function resolveFilter($filter)
    {
        return new $this->filters[$filter];
    }
}
