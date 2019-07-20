<?php


namespace App\Services\Product;

use App\Color;
use Illuminate\Http\Request;

class ProductService
{
    public $req;

    public function __construct(Request $request)
    {
        $this->req = $request;
    }

    private function getColorsById(array $ids)
    {
        $colors = Color::whereIn('id', $ids);
        return $colors;
    }

    public function addProducts()
    {
        $colors = $this->req->input('colors');

        return $this->sendResponse($colors);
    }


    private function sendResponse($message, $status = 200)
    {
        return response()->json($message, $status);
    }
}
