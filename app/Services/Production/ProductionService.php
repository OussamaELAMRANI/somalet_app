<?php


namespace App\Services\Production;


use App\Order;
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

   /**
    * Get Stock Detail for Sole product with client [Discount]
    * Search by @param $name
    *
    * @return array
    */
   public function getAllProductionsDetail($name = null)
   {
      $discount = false;
      $allPF = ProductSize::with('productionOrders', 'product', 'size', 'product.clients', 'commands')
         ->whereHas('product', function ($query) use ($name) {
            $query->where('type', "PF")
               ->where('name', 'LIKE', "%{$name}%");
         });

//      return $allPF->get();

      if ($this->req->has('client_id')) {
         $discount = $this->hasDiscounting(clone $allPF, $this->req->get('client_id'));
      }

      $allPF = $allPF->get()->toArray();

      $allQte = [];
      foreach ($allPF as $pf) {
         if (!key_exists($pf['product']['name'], $allQte)) {
            $allQte[$pf['product']['name']] =
               [
                  'id' => $pf['product']['id'],
                  'name' => $pf['product']['name'],
                  'sell' => $pf['product']['sell_price'],
                  'buy' => $pf['product']['buy_price'],
                  'discount' => ($discount) ? $pf['product']['clients'][0]['pivot']['discount'] : 0,
                  'qte' => [],
               ];
         }
         if (count($pf['production_orders']) > 0) {
            $allQte[$pf['product']['name']]['qte'][] =
               $this->getCommandQte($pf['id'], $pf['production_orders'], $pf['size']['size'], $pf['commands']);
         }
      }

      return array_values($allQte);
   }

   /**
    * Helper of @function {$this->getAllProductionsDetail}
    *
    * @param $id
    * @param $ps
    * @param $size
    * @param $cmd
    * @return array
    */
   private function getCommandQte($id, $ps, $size, $cmd)
   {
      $qte = ['ps_id' => $id, 'order_quantity' => 0, 'size' => $size];

      foreach ($ps as $p) {
         if (in_array($p['state'], ['VALID', 'RECEPTION'])) {
            $qte['order_quantity'] += $p['pivot']['order_quantity'];
         }
      }
      // List all qte and do diff
      foreach ($cmd as $c) {
         if (!$c['is_canceled']) {
            $qte['order_quantity'] -= $c['pivot']['qte'];
         }
      }

      return $qte;
   }

   /**
    * Check if has an Discount to avoid empty result []
    *
    * @param $pf
    * @param $client_id
    * @return bool
    */
   public function hasDiscounting($pf, $client_id): bool
   {
      $hasDiscounting = $pf->whereHas('product.clients', function ($query) use ($client_id) {
         $query->where('id', $client_id);
      })->get()->toArray();;

      return ($hasDiscounting) ? true : false;
   }

   public function addExternalCommand()
   {
      $order = $this->req->get('order'); // [cmd_number, date_cmd, client_id ]
      /**
       * [
       *    {
       *       buy, discount, price, qte
       *    }
       * ]
       */
      $order_ps = $this->req->get('order_ps');
      $newOrder = Order::create($order);
      return $newOrder->savePSItems($order_ps);
   }
}
