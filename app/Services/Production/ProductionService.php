<?php


namespace App\Services\Production;


use App\Arrival;
use App\Product;
use App\ProductionOrder;
use App\ProductSize;
use App\Services\AbstractService;
use Carbon\Carbon;

class ProductionService extends AbstractService
{
   public function addProductionOrder()
   {
      // ps_id
      $date_cmd = Carbon::now()->toDateString();
      $orders = $this->req->get('production_order');
      $newProductionOrder = ProductionOrder::create(['date_cmd' => $date_cmd]);
      // [
      //    { cmd_id, order_quantity, fabric_quantity }
      //]
      $newProductionOrder->addInternalOrder($orders);

      return $newProductionOrder;
   }

   public function getAllProductionsOrder()
   {
      return ProductionOrder::with('productSizes', 'productSizes.product', 'productSizes.size')->last();
   }

   public function listProductionOrder()
   {
      return ProductionOrder::last();
   }

   public function getProductionDetail(ProductionOrder $order)
   {
      return $order->load('productSizes', 'productSizes.product', 'productSizes.size');
   }

   public function orderNotFinished()
   {
      return ProductionOrder::Where('state', '<>', 'VALID')->get();
   }

   public function changeOrderState(ProductionOrder $order)
   {
      return $order->update(['state' => $this->req->get('state')]);
   }

   public function validReception(ProductionOrder $order)
   {
      $products = $this->req->input('products');

      foreach ($products as $p) {
         $productSize = $order->productSizes();
         $ps = $p['pivot']['ps_id'];
         $old = $productSize->wherePivot('ps_id', $ps)->first();

         $newQte = $p['pivot']['fabric_quantity'];
         $oldQte = $old['pivot']['fabric_quantity'];
//         $qteCmd = $old['pivot']['order_quantity'];

//         if ($qteCmd <= ($newQte+$oldQte))
         $productSize->updateExistingPivot($ps,
            [
               'fabric_quantity' => ($newQte + $oldQte),
               'price' => 0,
               'sell_price' => 0,
            ]);
      }

      if ($this->isComplete($order->load('productSizes')->toArray()))
         $order->update(['state' => 'RECEPTION']);

      return $order->load('productSizes');
   }

   public function isComplete($product): bool
   {
      foreach ($product['product_sizes'] as $p) {
         if ($p['pivot']['order_quantity'] > $p['pivot']['fabric_quantity']) {
            return false;
         }
      }
      return true;
   }

   public function getAllProductionsDetail()
   {
      $allPF = ProductSize::with('productionOrders', 'product', 'size')
         ->get()->toArray();
      $allQte = [];
      foreach ($allPF as $pf) {
         if (count($pf['production_orders']) > 0) {
            $allQte[$pf['product']['name']][] =
               $this->getCommandQte($pf['production_orders'], $pf['size']['size'], $pf['product']['buy_price'], $pf['product']['sell_price']);
         }
      }
      return $allQte;
   }

   public function getCommandQte($ps, $size, $buy, $price)
   {
      $qte = ['order_quantity' => 0, 'size' => null, 'name' => null, 'sell_price' => 0];

      foreach ($ps as $p) {
         if ($p['state'] === 'VALID') {
            $qte['order_quantity'] += $p['pivot']['order_quantity'];
            $qte['size'] = $size;
            $qte['buy_price'] = $buy;
            $qte['sell_price'] = $price;
         }
      }

      return $qte;
   }

   public function searchByRef($ref = '')
   {
      $validProducts = [];
      $pattern = "/{$ref}/i";
      $products = $this->getAllProductionsDetail();

      $keys = array_keys($products);
      $result = preg_grep($pattern, $keys);

      if (is_null($result)) {
         return [];
      }

      foreach ($result as $name) {
         $validProducts[$name] = $products[$name];
      }

      return $validProducts;
   }
}
