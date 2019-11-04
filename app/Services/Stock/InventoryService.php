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
      $allProducts = Product::with('color', 'arrivals', 'clients', 'unit', 'orders')
         ->get()->groupBy('reference');
      $client_id = $this->req->get('client_id');
      $getQte = $this->getFilterQte($allProducts->toArray(), $client_id);
//      return response()->json($allProducts, Response::HTTP_OK);
      return $getQte;
   }

   public function searchByRef($ref = '')
   {
      $validProducts = [];
      $pattern = "/{$ref}/i";
      $products = $this->getDetailStock();
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
            $qte_order = [];
            $sell = [];
            $crHT = [];

            $result[$p['name']] = [
               'product_id' => $p['id'],
               'reference' => $p['reference'],
               'name' => $p['name'],
               'unity' => $p['unit']['name'],
               'price' => [],
               'ht' => [],
               'QTE' => [],
               'orders_qte' => [],
               'discount' => 0,
            ];

            // O(n)
            foreach ($p['arrivals'] as $arr) {
               if ($arr['state'] == 'VALID') {

                  $sell[] = ($arr['pivot']['sell_price']) ?? 0;
                  $crHT [] = ($arr['pivot']['price_unit_ht']) ?? 0;

                  $qte[$arr['pivot']['qte_rapport_reception']][] = $arr['pivot']['qte_reception'];
                  $result[$p['name']]["QTE"] = $qte;

               }
            }

            // O(n)
            foreach ($p['orders'] as $order) {

               if (is_null($order['deleted_at'])) {

                  $qte_order[$order['pivot']['qte_rapport']][] = $order['pivot']['qte'];
                  $result[$p['name']]["orders_qte"] = $qte_order;

               }
            }

            if (count($sell) != 0)
               $result[$p['name']]['price'] = array_sum($sell) / count($sell);
            if (count($crHT) != 0)
               $result[$p['name']]['ht'] = array_sum($crHT) / count($crHT);
            // Filter Quantity for each rapport Qte


            $totalQte = [];

            foreach (array_keys($result[$p['name']]['QTE']) as $key) {
               $order_qte_total = 0;
               $qte_total = array_sum($result[$p['name']]['QTE'][$key]);

               if (array_key_exists($key, $result[$p['name']]['orders_qte']))
                  $order_qte_total = array_sum($result[$p['name']]['orders_qte'][$key]);

               $totalQte [$key] = $qte_total - $order_qte_total;
            }


            $result[$p['name']]['QTE_TOTAL'] = $totalQte;


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


   public function validContainer($arrival_id)
   {

      $products = $this->req->input('products'); // [id, sell_price]
      $arr = Arrival::find($arrival_id);

      foreach ($products as $p) {
         $arr->product()->wherePivot('rapport_qte', $p['pivot']['rapport_qte'])->updateExistingPivot(
            $p['id'], [
            'sell_price' => $p['pivot']['sell_price'],
            'price_unit_ht' => $p['pivot']['price_unit_ht'],
            'qte_validation' => $p['pivot']['qte_validation'],
            'qte_rapport_validation' => $p['pivot']['qte_rapport_validation'],
         ]);
         $arr->update(['state' => 'VALID']);
      }

      $arr->update(['state' => 'VALID']);

      return response()->json($arr->with('product')->get());
   }

   public function validAllContainer($arrival_id)
   {
      $products = $this->req->input('products'); // [id, sell_price]
      $arr = Arrival::find($arrival_id);

      foreach ($products as $p) {
         $arr->product()->wherePivot('rapport_qte', $p['pivot']['rapport_qte'])->updateExistingPivot(
            $p['id'], [
            'sell_price' => $p['pivot']['sell_price'],
            'price_unit_ht' => $p['pivot']['price_unit_ht'],
            'qte_validation' => $p['pivot']['qte_reception'],
            'qte_rapport_validation' => $p['pivot']['qte_rapport_reception'],
         ]);
         $arr->update(['state' => 'VALID']);
      }
      $arr->update(['state' => 'VALID']);

      return response()->json($arr->with('product')->get());
   }

   public function notYetValid()
   {
      return Arrival::WhereNotIn('state', ['VALID'])->with('product', 'provider', 'user')->get();
   }

}