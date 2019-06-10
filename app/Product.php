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
    function subcategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function pointures()
    {
        return $this->hasMany(Pointure::class, 'product_id');
    }
//    function larrival()
//    {
//        return $this->hasMany(LArrival::class, 'product_id');
//    }

    /**
     * Haves a Pivot table Order_items
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function arrival()
    {
        return $this->belongsToMany(Arrival::class, 'l_arrivals', 'product_id', 'arrival_id');
    }

}
