<?php


namespace App\Services\Commands;


use App\Arrival;
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

   public function addCommand()
   {
      $order = $this->req->input('order');
      $products = $this->req->input('products');

      // Add Order 1st
      $order['date_cmd'] = Carbon::parse($order['date_cmd'])->toDate();
//       $order['user_id'] = $this->req->user()->id; todo you know what

      $exist = Order::where('cmd_number', $order['cmd_number'])->first();

      if (!$exist) {
         $res = Order::create($order);
         $res->saveItems($products);
         return response()->json(['data' => $res, 'message' => "Added success"], 201);
      }
//
      return response()->json("Already Exist (Change the cmd_number) !", 406); // Not Acceptable [STATUE]
   }

   public function getCommands()
   {
      return Order::with('client', 'product','product.provider')->last();
   }

   public function getCommandById($cmd_number)
   {
      $order = Order::where('cmd_number', $cmd_number)->with('client', 'product', 'payments')->first();
      return $order;
   }

}
