<?php


namespace App\Services\Product;

use App\Color;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductService
{
   public $req;

   public function __construct(Request $request)
   {
      $this->req = $request;
   }

//    private function getColorsById(array $ids)
//    {
//        $colors = Color::whereIn('id', $ids)->get();
//        return $colors;
//    }

   public function addProducts()
   {
      $portrait_url = '';
      $now = Carbon::now();
      $colors = json_decode($this->req->input('colors'), true);
      $product = json_decode($this->req->input('product'), true);

      if ($this->req->has('img') && $this->req->file('img')) {
         try {
            $year = $now->year;
            $month = $now->month;
            $portrait_url = Storage::disk('public')->putFile("products/{$year}/{$month}", $this->req->file('img'));
         } catch (\Exception $e) {
            return response(['error' => $e->getMessage()]);
         }
      }

      if ($colors) {
         $products = array_map(function ($color) use ($product, $now, $portrait_url) {
            $product['name'] = $product['name'] . "/" . $color['name'];
            return array_merge($product, ['img' => $portrait_url, 'color_id' => $color['id'], 'created_at' => $now->toDateTimeString(), 'updated_at' => $now->toDateTimeString()]);
         }, $colors);
         Product::insert($products);
      } else
         Product::create(array_merge($product, ['img' => $portrait_url]));


      return $this->sendResponse(['message' => "Products successfully added"], 201);
   }


   public function getProduct($id)
   {
//        return response()->json(Product::->paginate(20), 200);

      return response()->json(Product::filter($this->req)->findOrFail($id), 200);
//            >load('provider', 'unit', 'color', 'subcategory'));
   }


   private function sendResponse($message, $status = 200)
   {
      return response()->json($message, $status);
   }
}
