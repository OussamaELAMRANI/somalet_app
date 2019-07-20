<?php


namespace App\Services\Product;

use App\Color;
use App\Product;
use Carbon\Carbon;
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
        $now = Carbon::now()->toDateTimeString();

        $products = array_map(function ($color) use ($product, $now) {
            $product['name'] = $product['name'] . "/" . $color['name'];
            return array_merge($product, ['color_id' => $color['id'], 'created_at' => $now, 'updated_at' => $now]);
        }, $colors->toArray());

        Product::insert($products);
        return $this->sendResponse(['message' => "Products successfully added"], 201);
    }


    private function sendResponse($message, $status = 200)
    {
        return response()->json($message, $status);
    }
}
