<?php


namespace App\Services\Stock;


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
      $allProducts = Product::with('color', 'arrivals', 'clients')
         ->get()->groupBy('reference');
      $client_id = $this->req->get('client_id');
      $getQte = $this->getFilterQte($allProducts->toArray(), $client_id);
//      return response()->json($allProducts, Response::HTTP_OK);
      return $getQte;
   }

   private function getFilterQte($products_group, $client_id = null)
   {
      $qte = [];
      $result = [];
      $sell = [];
      $crHT = [];

      foreach ($products_group as $g) {
         foreach ($g as $p) {

            $result[$p['name']] = [
               'product_id' => $p['id'],
               'reference' => $p['reference'],
               'name' => $p['name'],
               'price' => [],
               'ht' => [],
               'QTE' => [],
               'discount' => 0,
            ];

            foreach ($p['arrivals'] as $arr) {
               if ($arr['state'] == 'VALID') {
                  $sell[] = ($arr['pivot']['sell_price']) ?? 0;
                  $crHT [] = ($arr['pivot']['price_unit_ht']) ?? 0;

                  $qte[$arr['pivot']['rapport_qte']][] = $arr['pivot']['qte_facture'];
                  $result[$p['name']]["QTE"] = $qte;

               }
            }

            // Filter Quantity for each rapport Qte
            $result[$p['name']]['price'] = array_sum($sell) / count($sell);
            $result[$p['name']]['ht'] = array_sum($crHT) / count($crHT);

            $aa = array_map(function ($q) {
               return array_sum(($q));
            }, $result[$p['name']]['QTE']);

            $result[$p['name']]['QTE_TOTAL'] = $aa;


            if (!is_null($client_id)) {
               foreach ($p['clients'] as $client) {
                  if ($client_id == $client['id'])
                     $result[$p['name']]['discount'] = $client['pivot']['discount'];
               }
            }
         }
      }
      return $result;
   }

}
