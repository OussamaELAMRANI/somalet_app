<?php

namespace App\Http\Controllers;

use App\Arrival;
//use App\Reception;
use App\Product;
use App\Services\Stock\InventoryService;
use Illuminate\Http\Request;

class ReceptionController extends Controller
{
   private $inventory;

   public function __construct(InventoryService $inventory)
   {
      $this->inventory = $inventory;
   }

   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      return Arrival::WhereNotIn('state', ['RECEPTION', 'VALID'])->with('product', 'provider', 'user')->get();
   }

   public function getNoValidArrivals()
   {
      return Arrival::Where('state', 'RECEPTION')->with('product', 'provider', 'user')->get();
   }

   public function validateArrival(Request $req, $arrival_id)
   {
      $marge = $req->input('marge');
      $products = $req->input('products'); // [id, sell_price]
      $arr = Arrival::findOrFail($arrival_id);

      foreach ($products as $p)
         $arr->product()->updateExistingPivot($p['id'],
            ['sell_price' => $p['pivot']['sell_price']],
            ['price_unit_ttc' => $p['pivot']['price_unit_ttc']]
         );

      $arr->update(['state' => 'VALID', 'taux_marge' => $marge]);

      return response()->json($arr);
   }

   private function filterStock(Request $req)
   {
      $allProduct = Product::with('arrivals', 'unit', 'color', 'user', 'clients')->get();
      $validProducts = $this->getQte($allProduct->toArray(), $req->input('client_id'));
      return $validProducts;
   }

   public function getInventories(Request $req)
   {
      $validProducts = $this->filterStock($req);
      return response()->json($validProducts, 200);
   }

   private function getQte(array $products, $client_id = null)
   {
      $a = [];

      foreach ($products as $p) {
         $qteF = 0;
         $qteR = 0;
         $vente = 0;
         $crHT = 0;
         $n = 0;
         foreach ($p['arrivals'] as $arr) {

            if ($arr['state'] == 'VALID') {
               $n++;
               $qteF += $arr['pivot']['qte_facture'];
               $qteR += $arr['pivot']['rapport_qte'];
               $vente += ($arr['pivot']['sell_price']) ?? 0;
               $crHT += ($arr['pivot']['price_unit_ht']) ?? 0;

               $a[$p['name']] = [
                  'product_id' => $p['id'],
                  'QTE' => $qteF,
                  'qte_rapport' => $qteR,
                  'qte_total' => ($qteR * $qteF),
                  'reference' => $p['reference'],
                  'prix' => 1, 'ht' => 1,
                  'name' => $p['name'],
                  'unity' => $p['unit']['name']];
            }
         }
         $a[$p['name']]['prix'] = ($n != 0) ? ($vente / $n) : 0;
         $a[$p['name']]['ht'] = ($n != 0) ? ($crHT / $n) : 0;

         if (!is_null($client_id)) {
            foreach ($p['clients'] as $client) {

               if ($client_id == $client['id']) {
                  $a[$p['name']]['discount'] = $client['pivot']['discount'];
               } else {
                  $a[$p['name']]['discount'] = 0;
               }

            }

         } else
            $a[$p['name']]['discount'] = 0;

         $n = 0;
      }


      return collect($a)->all();
   }

   public function searchOrder(Request $req, $product = '')
   {
      $validProducts = [];
      $pattern = "/{$product}/i";
      $products = $this->filterStock($req);
      $keys = array_keys($products);
      $result = preg_grep($pattern, $keys);
      if (is_null($result)) {
         return response()->json([], 200);
      } else {
         foreach ($result as $name) {
            $validProducts[] = $products[$name];
         }
      }
      return response()->json($validProducts, 200);
   }

   public function getDetailStock()
   {
      return $this->inventory->getDetailStock();
   }
}
