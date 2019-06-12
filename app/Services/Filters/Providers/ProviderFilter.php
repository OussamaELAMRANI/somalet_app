<?php


namespace App\Services\Filters\Providers;


use App\Services\Filters\FilterAbstract;

class ProviderFilter extends FilterAbstract
{
    protected $filters = [
        'by' => SearchFilter::class, // /api/provider?search=bla..bal&by=name||steName
        'with' => RelationsFilter::class, // /api/provider?with=product,user
    ];
}
