<?php


namespace App\Services\Commands;


use App\Order;
use Illuminate\Support\Carbon;
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
    * @return array
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

   public function addCommand(Request $req)
   {
      $order = $req->input('order');
      $products = $req->input('products');

      $productsFormated = $this->productFormatToAdd($products);


      // Add Order 1st
      $order['date_cmd'] = Carbon::parse($order['date_cmd'])->toDate();
      $order['status'] = 'OUT';
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

   public function getCommands($is_canceled = 0)
   {

      $orders = Order::where('is_canceled', $is_canceled);

      if ($this->req->has('from') && $this->req->has('to')) {
         $fromDate = $this->req->get('from');
         $toDate = $this->req->get('to');
         $orders->whereBetween('date_cmd', [$fromDate, $toDate]);
      } else {
         $toDay = Carbon::now();
         $orders->whereBetween('date_cmd', [$toDay->addDays(-1)->toDateString(), $toDay->addDays(1)->toDateString()]);
      }

      return $orders->with('client', 'product', 'product.provider')
         ->last();
   }

   /**
    * Used to check Order with Deep Detail
    *
    * @param Order $order
    * @return Order
    */
   public function getCommandById(Order $order)
   {
      return $order
         ->load('client', 'product', 'product.color',
            'productSize', 'productSize.product', 'productSize.product.color', 'productSize.size');
   }

   public function getCommandByIdToPrint($cmd_number)
   {
      $order = Order::where('cmd_number', $cmd_number)
         ->with('client', 'product', 'product.color',
            'productSize', 'productSize.product', 'productSize.product.color', 'productSize.size')->first()->toArray();

      $products = [];
      $productSize = $order['product_size'];

//      dd($productSize);

      foreach ($productSize as $ps) {
         if (!array_key_exists($ps['product']['name'], $products)) {

            $products[$ps['product']['name']][] = [
               'name' => $ps['product']['reference'],
               'color' => $ps['product']['color']['name'],
               'price' => $ps['product']['sell_price'],
               'qte' => $ps['pivot']['qte'],
               'discount' => $ps['pivot']['discount'],
            ];
         } else {
            $products[$ps['product']['name']][0]['qte'] += $ps['pivot']['qte'];
         }

      }

      return response(['products' => $products, 'order' => $order], 200);
   }

}
