<?php


namespace App\Services\Filters\Clients;


use App\Services\Filters\FilterAbstract;

class ClientFilter extends FilterAbstract
{
    protected $filters = [
        'by' => SearchFilter::class, // /api/client?search=bla..bal&by=name||steName
        'with' => RelationsFilter::class, // /api/client?with=product,user
        'between' => DatesFilter::class, // /api/client?between=product,user
    ];
}
