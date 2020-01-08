<?php


namespace App\Services\Product;

use App\Color;
use App\Product;
use App\Services\AbstractService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ProductService extends AbstractService
{

   public function addProducts()
   {
      $now = Carbon::now();
      $portrait_url = $this->insertImage($now);

      $colors = json_decode($this->req->input('colors'), true);
      $product = json_decode($this->req->input('product'), true);
      $sizes = json_decode($this->req->input('sizes'), true);
      $product_name = $product['name'];

      if ($colors) {
         foreach ($colors as $color) {
            $product['name'] = "{$product_name}/{$color['name']}";
            $db = $now->toDateTimeString();
            $color_merge = ['img' => $portrait_url, 'color_id' => $color['id'], 'created_at' => $db, 'updated_at' => $db];
            $products = array_merge($product, $color_merge);
            $newProduct = Product::create($products);
//            if ($sizes) {
            $newProduct->saveSizes($sizes, $now);
//            }
         }
      } else {
         Product::create($product);
      }

      return $this->sendResponse(['message' => "Products successfully added"], 201);

   }

   /**
    * @param  $now
    * @return string
    * @throws Exception $e
    */
   public function insertImage($now)
   {
      $portrait_url = '';
      if ($this->req->has('img') && $this->req->file('img')) {
         try {
            $year = $now->year;
            $month = $now->month;
            $portrait_url = Storage::disk('public')->putFile("products/{$year}/{$month}", $this->req->file('img'));
         } catch (Exception $e) {
            throw new Exception();
         }
      }
      return $portrait_url;
   }

   /**
    * Get A Product By Reference
    * @param $ref
    * @return JsonResponse
    */
   public function getProduct($ref)
   {
      return $this->sendResponse(Product::where('reference', $ref)->filter($this->req)->get());
   }

   public function getDistinctProductByName()
   {
      return Product::all()->groupBy('reference');
   }

   public function getProductById($id)
   {
      return response()->json(Product::filter($this->req)->findOrFail($id), 200);
   }

   public function searchProductByName($slot)
   {
      $products = Product::where('name', 'LIKE', "%$slot%")->filter($this->req)->get();
      return response()->json($products, 200);
   }
}
