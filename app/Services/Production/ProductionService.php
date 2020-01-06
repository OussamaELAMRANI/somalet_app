<?php


namespace App\Services\Production;


use App\ProductionOrder;
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


}
