<?php


namespace App\Services\Stock;


use App\Arrival;
use App\Product;
use App\Services\AbstractService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InventoryService extends AbstractService
{

   /**
    * Real Stock with Detail for No Sole Products
    *
    * @param string $name (Search)
    * @return array
    */
   public function getDetailStock($name = '')
   {
      $allProducts = Product::with('color', 'arrivals', 'clients', 'unit', 'orders')
         ->where('type', '<>', 'PF')
         ->where('name', 'LIKE', "%{$name}%")
         ->get()->groupBy('reference');

      $client_id = $this->req->get('client_id');
      $getQte = $this->getFilterQte($allProducts->toArray(), $client_id);

      return $getQte;
   }

   public function getAlertStock()
   {
      $allProducts = Product::with('color', 'arrivals', 'clients', 'unit', 'orders')
         ->get()->groupBy('reference');

      $getQte = $this->getFilterQte($allProducts->toArray());


      $alerts = collect($getQte)->filter(function ($product) {
         return $product['alert'] >= $product['totalQte'];
      });

      return array_values($alerts->toArray());
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

   /**
    * Filter Helper - Give the Real Stock
    *
    * @param $products_group
    * @param null $client_id
    * @return array
    */
   private function getFilterQte($products_group, $client_id = null)
   {
      $result = [];

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
               'alert' => $p['alertQte'],
               'totalQte' => 0,
               'orders_qte' => [],
               'discount' => 0,
            ];

            // O(n)
            foreach ($p['arrivals'] as $arr) {
               if ($arr['state'] == 'VALID') {

                  $sell[] = ($arr['pivot']['sell_price']) ?? 0;
                  $crHT [] = ($arr['pivot']['price_unit_ht']) ?? 0;
                  $qte_receptions = $arr['pivot']['qte_rapport_reception'];
                  $qte["{$qte_receptions}"][] = $arr['pivot']['qte_reception'];
                  $result[$p['name']]["QTE"] = $qte;

               }
            }

            // O(n)
            foreach ($p['orders'] as $order) {

               if (is_null($order['deleted_at']) && !$order['validate_canceled']) {

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

            $rest = 0;
            foreach ($totalQte as $k => $v) {
               $rest += $k * $v;
            }
            $result[$p['name']]['totalQte'] = $rest;

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
