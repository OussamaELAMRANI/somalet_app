<?php


namespace App\Services\Payments;


use App\Order;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PayementService
{
   private $req;

   public function __construct(Request $request)
   {
      $this->req = $request;
   }

   public function getOrderPrice($product)
   {
      $payment = 0;
      foreach ($product as $p) {
         $payment += $p['pivot']['qte_price'];
      }
      return $payment;
   }

   public function getOrderPayment($payments)
   {
      $payment = 0;
      foreach ($payments as $p) {
         $payment += $p['amount'];
      }
      return $payment;
   }

   private function availablePayment($cmd)
   {
      $order = Order::where('cmd_number', $cmd)->with('payments', 'product')->first();
      $amount = $this->getOrderPrice(collect($order)->get('product'));
      $payment = $this->getOrderPayment(collect($order)->get('payments'));

      $total = $payment - $amount;
      if ($total == 0)
         return 0;

      return $order->id;
   }

   public function addPayment($cmd)
   {
      $isAvailable = $this->availablePayment($cmd);
      if ($isAvailable != 0) {
         $payment = $this->req->input('payment');

         if ($payment['type'] == 'ESP')
            $payment['checkout_now'] = 1;

         $payment['payed_at'] = Carbon::parse($payment['payed_at'])->toDateString();
         $payment['date_deadline'] = Carbon::parse($payment['date_deadline'])->toDateString();

         $add_payement = array_merge(['cmd_id' => $isAvailable], $payment);

         $payments = Payment::create($add_payement);
         return response()->json(['payment' => $payments, 'message' => 'Payment added successfully !'], 201);
      }

      // type => [payed_at, date_deadline, amount, checkout_now], done

      return response()->json("Cannot add payment to this command !", 400);


      $add_payement = array_merge($cmd, $payment);

      $payments = Payment::create($add_payement);

      return response()->json(['data' => $payments, 'message' => "Added success"], 201);

   }

   // Caisse is payement has Bank=null, and Type = ESP
   public function getCheckout()
   {
      $checkout = Payment::where('in_bank', null)->where('type', 'ESP')->with('order', 'order.client')->get();
      return response()->json($checkout, 201);
   }

   // PortFeuille is payement has Bank=null, and Type = [EFF,CHQ]
   public function getCheques()
   {
      $checkout = Payment::where('in_bank', null)->whereIn('type', ['EFF', 'CHQ'])->with('order', 'order.client')->get();
      return response()->json($checkout, 201);
   }


   public function getPayments()
   {
      $payments = Payment::with('')->get();
      return response()->json($payments, 200);
   }
}
