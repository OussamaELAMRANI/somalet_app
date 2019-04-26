<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    // this param means all the col in Provider table are fillable
    protected $guarded = [];

    function products(){
        return $this->hasMany(Product::class, 'provider_id');
    }
}
