<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Arrival extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected $primaryKey = 'n_facture';

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
    public function getKeyName()
    {
        return $this->primaryKey;
    }
}
