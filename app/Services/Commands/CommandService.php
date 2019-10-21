<?php


namespace App\Services\Commands;


use App\Arrival;
use App\Http\Requests\Orders\CommandRequest;
use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CommandService
{
   private $req;

   public function __construct(Request $request)
   {
      $this->req = $request;
   }

   /**
    * @param array $products
    * [
    *    0 => ['qte'=>{rapport: qte}, discount:0, price:0, product_id:0],
    *    1 => ['qte'=>{rapport: qte}, discount:0, price:0, product_id:0]
    *    ...
    * ]
    */
   private function productFormatToAdd(array $products): array
   {
      $oldProducts = collect($products);
      $formated = [];

      foreach ($oldProducts as $product) {
         foreach ($product['qte'] as $rapport => $qte) {

            $formated[] = array(
               'product_id' => $product['product_id'],
               'qte' => $qte,
               'qte_rapport' => $rapport,
               'discount' => (float)$product['discount'],
               'price' => $product['price'],
            );

         }
      }

      return $formated;
   }

   public
   function addCommand(Request $req)
   {
      $order = $req->input('order');
      $products = $req->input('products');

      $productsFormated = $this->productFormatToAdd($products);


      // Add Order 1st
      $order['date_cmd'] = Carbon::parse($order['date_cmd'])->toDate();
//       $order['user_id'] = $this->req->user()->id; todo you know what

      $exist = Order::where('cmd_number', $order['cmd_number'])->first();

      if (!$exist) {
         $res = Order::create($order);
         $res->saveItems($productsFormated);
         return response()->json(['data' => $res, 'message' => "Added success"], 201);
      }
//
      return response()->json("Already Exist (Change the cmd_number) !", 406); // Not Acceptable [STATUE]
   }

   public function getCommands()
   {
      return Order::with('client', 'product', 'product.provider')->last();
   }

   public function getCommandById($cmd_number)
   {
      $order = Order::where('cmd_number', $cmd_number)->with('client', 'product', 'product.color')->first();
      return $order;
   }

   public function getOrders()
   {
//      Order::filter($this->req);
   }

}
