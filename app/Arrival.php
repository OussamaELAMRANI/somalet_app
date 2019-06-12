<?php

namespace App;

use App\Services\Filters\Arrivals\ArrivalFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Arrival extends Model
{
    use SoftDeletes;
    protected $guarded = [];
//    protected $primaryKey = 'n_facture';


    public function scopeFilter(Builder $builder, Request $req, array $filters = [])
    {
        return (new ArrivalFilter($req))->add($filters)->filter($builder);
    }

    public function scopeLast(Builder $builder)
    {
        return $builder->orderBy('id','desc')->get();
    }

    function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }
//    function larrival()
//    {
//        return $this->hasMany(LArrival::class, 'arrival_id');
//    }


    public function getKeyName()
    {
        return $this->primaryKey;
    }

//    public function products()
//    {
//        return $this->belongsToMany(Product::class);
//    }

    /**
     * Haves a Pivot table l_arrivals
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function product()
    {
        return $this->belongsToMany(Product::class, 'l_arrivals',
            'arrival_id', 'product_id');
    }
    /**
     * Save All Order Items for this Client
     *
     * @param array $items
     * @param $userId
     * @return double Total
     */
    public function saveItems(array $items, $arrival_id)
    {
        // Fill array with IDs
        $arrival_id = array_fill(0, count($items), $arrival_id);

        $orderItem = array_map(function ($itm, $id) {
            return array_merge($itm, ['arrival_id' => $id]);
        }, $items, $arrival_id);

        $this->product()->attach($orderItem);

        // Updating original Inventory
//        foreach ($items as $prod) {
//            $upProduct = $this->product()->find($prod['productId']);
//            $upProduct->inventory -= $prod['quantity'];
//            $upProduct->save();
//        }
        return $this;
    }
}
