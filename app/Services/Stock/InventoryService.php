<?php


namespace App\Services\Stock;


use App\Arrival;
use App\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InventoryService
{
   private $req;

   public function __construct(Request $request)
   {
      $this->req = $request;
   }

   public function getDetailStock()
   {
      $allProducts = Product::with('color', 'arrivals', 'clients', 'unit')
         ->get()->groupBy('reference');
      $client_id = $this->req->get('client_id');
      $getQte = $this->getFilterQte($allProducts->toArray(), $client_id);
//      return response()->json($allProducts, Response::HTTP_OK);
      return $getQte;
   }

   private function getFilterQte($products_group, $client_id = null)
   {
//      $qte = [];
      $result = [];
//      $sell = [];
//      $crHT = [];
//return $products_group;

      foreach ($products_group as $g) {
         foreach ($g as $p) {

            $qte = [];
            $sell = [];
            $crHT = [];

            $result[$p['id']] = [
               'product_id' => $p['id'],
               'reference' => $p['reference'],
               'name' => $p['name'],
               'unity' => $p['unit']['name'],
               'price' => [],
               'ht' => [],
               'QTE' => [],
               'discount' => 0,
            ];

            foreach ($p['arrivals'] as $arr) {
               if ($arr['state'] == 'VALID') {

                  $sell[] = ($arr['pivot']['sell_price']) ?? 0;
                  $crHT [] = ($arr['pivot']['price_unit_ht']) ?? 0;

                  $qte[$arr['pivot']['qte_rapport_reception']][] = $arr['pivot']['qte_reception'];
                  $result[$p['id']]["QTE"] = $qte;

               }
            }
            if (count($sell) != 0)
               $result[$p['id']]['price'] = array_sum($sell) / count($sell);
            if (count($crHT) != 0)
               $result[$p['id']]['ht'] = array_sum($crHT) / count($crHT);
            // Filter Quantity for each rapport Qte

            $aa = array_map(function ($q) {
               return array_sum(($q));
            }, $result[$p['id']]['QTE']);

            $result[$p['id']]['QTE_TOTAL'] = $aa;


            if (!is_null($client_id)) {
               foreach ($p['clients'] as $client) {
                  if ($client_id == $client['id'])
                     $result[$p['id']]['discount'] = $client['pivot']['discount'];
               }
            }
         }
      }
      return $result;
   }


   public function validContainer($arrival_id)
   {

      $products = $this->req->input('products'); // [id, sell_price]
      $arr = Arrival::find($arrival_id);

      foreach ($products as $p) {
         // TODO: Here if $product_id is duplicated ==> All Updated will have the same values !
         $arr->product()->updateExistingPivot(
            $p['id'], [
            'sell_price' => $p['pivot']['sell_price'],
            'price_unit_ht' => $p['pivot']['price_unit_ht'],
//            'price_unit_ttc' => $p['pivot']['price_unit_ht'],
            'qte_reception' => $p['pivot']['qte_reception'],
            'qte_rapport_reception' => $p['pivot']['qte_rapport_reception'],
         ]);
         $arr->update(['state' => 'VALID']);
      }

      $arr->update(['state' => 'VALID']); // 'taux_marge' => 1 {REMOVED}

      return response()->json($arr->with('product')->get());
   }


   public function notYetValid()
   {
      return Arrival::WhereNotIn('state', ['VALID'])->with('product', 'provider', 'user')->get();
   }

}
