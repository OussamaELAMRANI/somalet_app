<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Arrival extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    function provider()
    {
        return $this->belongsTo(Provider::class);
    }
    function user()
    {
        return $this->belongsTo(User::class);
    }
    function larrival()
    {
        return $this->hasMany(LArrival::class, 'arrival_id');
    }
    function sub_categories()
    {
        return $this->hasMany(SubCategory::class, 'category_id');
    }
}
