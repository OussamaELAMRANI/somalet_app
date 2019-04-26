<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    function color()
    {
        return $this->belongsTo(Color::class);
    }

    function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
