<?php


namespace App\Services\Product;

use App\Color;
use App\Product;
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
        $colors = Color::whereIn('id', $ids)->get();
        return $colors;
    }

    public function addProducts()
    {
        $colors = $this->getColorsById($this->req->input('colors'));
        $product = $this->req->input('product');


        $products = array_map(function ($color) use ($product) {
            $product['name'] = $product['name'] . "/" . $color['name'];
            return array_merge($product, ['color_id' => $color['id']]);
        }, $colors->toArray());

        return $this->sendResponse($products, 201);

        $newProds = Product::createMany($products);

        return $this->sendResponse($newProds, 201);
    }


    private function sendResponse($message, $status = 200)
    {
        return response()->json($message, $status);
    }
}
