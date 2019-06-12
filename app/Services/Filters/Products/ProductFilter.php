<?php


namespace App\Services\Filters\Products;


use App\Services\Filters\FilterAbstract;

class ProductFilter extends FilterAbstract
{
    protected $filters = [
        'by' => SearchFilter::class, // /api/products?search=bla..bal&by=name||ref
        'with' => RelationsFilter::class, // /api/products?with=product,user
    ];
}
