<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;
    protected $fillable =['name'];

    public $timestamps = false;

    function products()
    {
        return $this->hasMany(Product::class, 'unit_id');
    }
}
