<?php

namespace App;

use App\Services\Filters\Providers\ProviderFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Provider extends Model
{
    use SoftDeletes;
    // this param means all the col in Provider table are fillable
    protected $guarded = [];

    public function scopeFilter(Builder $builder, Request $req, array $filters = [])
    {
        return (new ProviderFilter($req))->add($filters)->filter($builder);
    }

    function products()
    {
        return $this->hasMany(Product::class, 'provider_id');
    }

    function arrivals()
    {
        return $this->hasMany(Arrival::class, 'provider_id');
    }
}
