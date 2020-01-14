<?php


namespace App\Services\Clients;


use App\Client;
use App\Order;
use App\Payment;
use App\Product;
use App\Services\AbstractService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientService extends AbstractService
{

   private function collapseProductDiscount($products)
   {
      $filtered = [];
      foreach ($products as $p) {
         if (!array_key_exists($p['reference'], $filtered))
            $filtered[$p['reference']] = [
               'reference' => $p['reference'],
               'discount' => $p['pivot']['discount'],
            ];
      }
      return $filtered;
   }

   public function getClientDetail($id)
   {
      $clients = Client::with('products')->findOrFail($id);
      return response()->json($clients, 200);
   }

   public function getAllClients()
   {
      $clients = Client::desc()->get();
      return response()->json($clients, 200);
   }


   /**
    * @param $id [client_id]
    *
    * @return JsonResponse
    */
   public function getMovementClient($id)
   {
      $res = [];
      $dates = explode(',', \request()->query('between'));

      foreach ($dates as $key) {
         $d = Carbon::parse($key)->toDateString();
         $res[] = $d;
      }
      if (count($res) < 2) {
         $res[0] = Carbon::now()->toDateString();
         $res[1] = Carbon::now()->toDateString();
      }

      // Filter By [Client_id, Date_from, to]
      $client = Client::find($id);
      $orders = $client->commands()->whereBetween('date_cmd', [$res[0], $res[1]])
         ->with('product', 'productSize')
         ->get()->groupBy('date_cmd')->toArray();
      $payments = $client->payments()->whereBetween('date_deadline', [$res[0], $res[1]])
         ->join('payment_types AS t', 't.id', '=', 'payments.typed')
         ->get()
         ->groupBy('date_deadline')->toArray();


      $sold = $this->getClientSold($id, $res[0]);
      $sold_today = ($this->getClientSold($id, $res[1]));

      $movements = [];
      foreach ($orders as $k => $v) {
         foreach ($v as $order)
            $movements[$k][] = $this->getOrderAmount($order);
      }

      // O(n)
      foreach ($payments as $k => $v) {
         if (isset($movements[$k]))
            $movements[$k] = array_merge($movements [$k], $v);
         else
            $movements[$k] = $v;
      }
      ksort($movements);


      return response()->json([
         'client' => $client,
         'movements' => $movements,
         'sold' => $sold,
         'sold_today' => $sold_today
      ], 200);

   }

   public function getOrderAmount($orders)
   {
      $commands = collect($orders);
      $price = 0;


      if ($commands->get('status') === 'OUT') {

         $products = $commands->get('product');
         if (count($products) > 0)
            foreach ($products as $p) {
               $price +=
                  $p['pivot']['qte'] *
                  $p['pivot']['qte_rapport'] *
                  ($p['pivot']['price'] - $p['pivot']['discount']);
            }

      } else {
         $shoesProduct = $commands->get('product_size');
         if (count($shoesProduct) > 0)
            foreach ($shoesProduct as $p) {
               $price += $p['pivot']['qte'] * ($p['pivot']['price'] - $p['pivot']['discount']);
            }
      }

      $commands['amount'] = $price;

      return $commands;
   }


   /**
    * Get client Sold until date $to
    * @param $client_id
    * @param null $to
    * @return float|int
    */
   public function getClientSold($client_id, $to = null)
   {
      if (is_null($to))
         $to = Carbon::now()->toDateString();

      $orders = $this->getOrdersAt($client_id, $to);
      $payments = $this->getPaymentAt($client_id, $to);

      return $this->getSold($orders, $payments);;
   }

   public function getOrdersAt($client_id, $to_date)
   {
//      $firstCommand = DB::table('orders AS o')
//         ->join('order_items as it', 'it.cmd_id', '=', 'o.id')
//         ->where('o.client_id', '=', $client_id)
//         ->where('o.date_cmd', '<=', $to_date)
//         ->get();
      $firstCommand = Order::with('product', 'productSize')
         ->where('date_cmd', '<=', $to_date)
         ->whereHas('client', function ($c) use ($client_id) {
            $c->where('id', '=', $client_id);
         })->get()->toArray();

//      dd($firstCommand);

      return ($firstCommand);

//      if ($firstCommand->where('o.status', '=', 'OUT')->exists()) {
//      $cmd = $firstCommand->join('order_items as it', 'it.cmd_id', '=', 'o.id');
//      } else {
//         $cmd = $firstCommand->join('order_ps as ps', 'ps.cmd_id', '=', 'o.id');
//      }

//      $cmd->where('o.client_id', '=', $client_id)
//         ->where('o.date_cmd', '<=', $to_date)
//         //->get('date_cmd')
//         ->get();

//      return collect($firstCommand);
   }

   public function getPaymentAt($client_id, $to_date)
   {
      $payments = DB::table('payments AS p')
         ->where('p.client_id', '=', $client_id)
         ->where('p.payed_at', '<=', $to_date)
         ->where('p.state', null)
         //->get('date_cmd')
         ->get();

      return collect($payments);
   }

   public function getSold($orders, $payments)
   {
      $commands = collect($orders);
      $money = collect($payments);
      $price = [];

      foreach ($commands as $command) {
         if ($command['status'] === 'OUT') {
            foreach ($command['product'] as $p) {
               $price[] = $p['pivot']['qte'] * $p['pivot']['qte_rapport'] * ($p['pivot']['price'] - $p['pivot']['discount']);
            }
         }
         else{
            foreach ($command['product_size'] as $p) {
               $price[] = $p['pivot']['qte'] * ($p['pivot']['price'] - $p['pivot']['discount']);
            }
         }
      }

      $amount = $money->map(function ($p) {
         return $p->amount;
      });

      $price = array_sum($price);
      $amount = array_sum($amount->toArray());


      return ($price - $amount);
   }
}
