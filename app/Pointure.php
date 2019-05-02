<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pointure extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
