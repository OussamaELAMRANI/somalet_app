<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LArrival extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function arrival()
    {
        return $this->belongsTo(Arrival::class);
    }
    function receptions()
    {
        return $this->hasMany(Reception::class, 'reception_id');
    }
}
