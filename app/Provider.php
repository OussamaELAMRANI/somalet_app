<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    use SoftDeletes;
    // this param means all the col in Provider table are fillable
    protected $guarded = [];

    function products(){
        return $this->hasMany(Product::class, 'provider_id');
    }
    function arrivals(){
        return $this->hasMany(Arrival::class, 'provider_id');
    }
}
