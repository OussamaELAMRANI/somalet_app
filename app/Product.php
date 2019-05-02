<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

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

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function pointures()
    {
        return $this->hasMany(Pointure::class, 'product_id');
    }
    function larrival()
    {
        return $this->hasMany(LArrival::class, 'product_id');
    }
}
