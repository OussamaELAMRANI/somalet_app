<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ProductRequest;
use App\Product;
use App\Provider;
use App\Services\Product\ProductService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
   public $service;

   public function __construct(ProductService $productService)
   {
      $this->service = $productService;
   }

   /**
    * Display a listing of the Product.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      $data = Product::with(['unit', 'color', 'provider', 'subcategory'])->orderBy('updated_at', 'desc')->paginate(10);
      return response()->json($data);
   }


   public function newProducts()
   {
      return $this->service->addProducts();
   }

   public function store(Request $req)
   {
      $existe = Product::find($req->input('id'));
      $colorsId = $req->input('colors');

//        dd($colorsId);
      $colors = [];
      foreach (\request()->input('colors') as $color)
         $colors[] = $color->id;
      return response()->json(['count' => count($colorsId), 'dada' => $colors]);

      $portrait_url = '';
      if ($req->has('img') && $req->file('img')) {
         try {
            $year = Carbon::parse($req->input('date'))->year;
            $month = Carbon::parse($req->input('date'))->month;
            $portrait_url = Storage::disk('public')->putFile("products/{$year}/{$month}", $req->file('img'));
         } catch (\Exception $e) {
            return response(['error' => $e->getMessage()]);
         }
      }
      if (!$existe) {
         foreach ($colorsId as $color) {
            $data = Product::create([
               'reference' => $req->input('data.reference'),
               'name' => "{$req->input('data.name')}/{$color->name}",
               'color_id' => $color->id,
               'unit_id' => $req->input('data.unit_id'),
               'description' => $req->input('data.description'),
               'remark' => $req->input('data.remark'),
               'alertQte' => $req->input('data.alertQte'),
               'rapport' => $req->input('data.rapport'),
               'provider_id' => $req->input('data.provider_id'),
               'subcategory_id' => $req->input('data.subcategory_id'),
               'img' => $portrait_url,
            ]);
         }
      } else {
         $data = $existe;
         $existe->update([
//                'reference' => $req->input('reference'),
            'name' => $req->input('name'),
            'color_id' => $req->input('color_id'),
            'unit_id' => $req->input('unit_id'),
            'description' => $req->input('description'),
            'remark' => $req->input('remark'),
            'provider_id' => $req->input('provider_id'),
            'subcategory_id' => $req->input('subcategory_id'),
            'rapport' => $req->input('rapport'),
            'img' => $portrait_url,
         ]);
      }

      return response()->json($data, 201);
   }

   /**
    * Display the specified resource.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
   public function show($id)
   {
      return $this->service->getProduct($id);
   }


   /**
    * Remove the specified resource from storage.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
   public function destroy(Product $id)
   {
      $v = $id::destroy($id->id);
      return response()->json(['message' => 'bien supprimé'], 201);
   }

   /**
    * @param Request $req
    * @param string $searchValue
    * @return Response
    */
   public function search(Request $req)
   {
      return response()->json(Product::filter($req)->paginate(100), Response::HTTP_OK);

   }

   public function distinctProductByName()
   {
      return $this->service->getDistinctProductByName();
   }
}
