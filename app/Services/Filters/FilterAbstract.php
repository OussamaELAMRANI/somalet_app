<?php


namespace App\Services\Filters;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class FilterAbstract
{
    protected $req;
    protected $filters = []; // [filter_name => ClassFilter::class]

    public function __construct(Request $request)
    {
        $this->req = $request;
    }

    public function filter(Builder $builder)
    {
        foreach ($this->getFilters() as $filter => $value) {
//            if ($filter != 'search')
            ($this->resolveFilter($filter)->filter($builder, $value));
        }
        return $builder;
    }

    public function add($customFilter)
    {
//        ['search' => $customFilter]
        $this->filters = array_merge($this->filters, $customFilter);
        return $this;
    }

    public function getFilters()
    {
        return $this->filterQuery($this->filters);
    }

    public function filterQuery($filter)
    {
//        return array_merge(array_filter($this->req->only(array_keys($filter))), ['search' => $this->req->value]);;
        return array_filter($this->req->only(array_keys($filter)));
    }

    public function resolveFilter($filter)
    {
        return new $this->filters[$filter];
    }
}
