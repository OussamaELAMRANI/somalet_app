<?php


namespace App\Services\Payments;


use App\Payment;
use App\PaymentType;
use App\Repositories\PaymentRepository;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PayementService
{
   private $req;

   /**
    * @var PaymentRepository
    */
   private $repository;

   public function __construct(Request $request, PaymentRepository $repository)
   {
      $this->req = $request;
      $this->repository = $repository;
   }

   public function getOrderPrice($product)
   {
      $payment = 0;
      foreach ($product as $p) {
         $payment += ($p['pivot']['price'] - $p['pivot']['discount']) * $p['pivot']['qte'] * $p['pivot']['qte_rapport'];
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

   public function addPayment($client_id)
   {

      $payment = $this->req->input('payment');

      $payment['payed_at'] = Carbon::parse($payment['payed_at'])->toDateString();
      $payment['date_deadline'] = Carbon::parse($payment['date_deadline'])->toDateString();

      $add_payement = array_merge(['client_id' => $client_id], $payment);

      $payments = Payment::create($add_payement);
      return response()->json(['payment' => $payments, 'message' => 'Payment added successfully !'], 201);

   }

   // Caisse is payement has Bank=null, and Type = ESP
   public function getCheckout()
   {
      $checkout = Payment::join('payment_types AS t', 't.id', '=', 'payments.typed')
         ->where('valid', 1)
         ->where('t.type', 'LIKE', 'ESPECE')
         ->with('client', 'toBank')
         ->get();

      return response()->json($checkout, 201);
   }

   // PortFeuille is payement has Bank=null, and Type = [EFF,CHQ]
   public function getCheques()
   {
      $checkout = Payment::join('payment_types AS t', 't.id', '=', 'payments.typed')
         ->where('in_bank', null)
         ->whereIn('t.type', ['EFFET', 'CHEQUE'])->with('client')->get();
      return response()->json($checkout, 201);
   }


   public function getPayments()
   {
      $payments = Payment::with('client')->get();
      return response()->json($payments, 200);
   }

   /**
    * Get Valid payments and Invalid
    */
   public function getPayments2Types()
   {
      $month = $this->req->get('month') ?? Carbon::today()->month;
      $year = $this->req->get('year') ?? Carbon::today()->year;

      $payments = $this->repository->getByDeadline($month, $year);

      $valid = [];
      $invalid = [];
      foreach ($payments as $p) {
         if ($p->done)
            $valid [] = $p;
         else
            $invalid [] = $p;
      }

      return response()->json([
         'valid' => $valid,
         'invalid' => $invalid
      ]);
   }


   public function getNoValidPayments()
   {
      $noValid = Payment::with('formBank')
         ->join('payment_types AS t', 't.id', '=', 'payments.typed')
         ->Join('clients AS c', 'c.id', '=', 'payments.client_id')
         ->select(['payments.*', 't.type', 'c.nom'])
         ->where('valid', 0)
         ->get();
      //->groupby('type');

      return response()->json([
         'payments' => $noValid,
         'grouped' => $noValid->groupby('type'),
      ]);
   }

   public function validatePayments()
   {
      $ids = $this->req->get('ids');

      $noValid = Payment::where('valid', 0)->whereIn('id', $ids);
      $beforeValidate = $noValid->get();

      try {
         $noValid->update(['valid' => 1]);
         return response()->json($beforeValidate);

      } catch (\Exception $exception) {
         return response()->json($exception, Response::HTTP_BAD_REQUEST);
      }
   }

   public function makeImpaye(Payment $payment)
   {
      if ($payment['state'] !== 'IGN') {
         $payment->update(['done' => 1, 'state' => 'IGN']);
         $new_payment = $payment;
         $new_payment['designation'] = "IMPAYÉ \n {$new_payment['chq_number']} \n {$new_payment['designation']}";
         $new_payment['state'] = 'JST';
         $new_payment['done'] = 0;
         $new_payment['date_deadline'] = Carbon::now()->toDateString();;
         unset($new_payment['id'], $new_payment['created_at'], $new_payment['updated_at']);
         Payment::create($new_payment->toArray());

         return response()->json('Update and create a new one');
      }
      return response()->json('This payment is already Ignored !', 401);
   }


   public function transferMoney()
   {
      $payment = $this->req->get('payment');
      $payment['payed_at'] = $payment['date_deadline'] = Carbon::now()->toDateString();
      $payment['operation'] = 'TRS';
      $payment['valid'] = 1;

      $typee = PaymentType::Where('type', 'ESPECE')->first();
      $created = $typee->payments()->create($payment);

      return response()->json($created, 201);
   }

   public function makeOperation()
   {
      $payment = $this->req->get('payment');
      $payment['payed_at'] = $payment['date_deadline'] = Carbon::now()->toDateString();
      $payment['operation'] = 'OPR';
      $payment['valid'] = 0;

      $created = Payment::create($payment)->first();

      return response()->json($created, 201);
   }


   public function getOutstandingPayments()
   {
      $impayes = Payment::where('done', 0)->where('state', 'JST')->where('adjust_by', null)->orderBy('id', 'desc')->get();
      return response()->json($impayes, 200);
   }

   public function adjustPayment(Payment $payment)
   {
      $p = $this->req->input('payment');

      $payment->update([
         'adjust_by' => $p['adjust_by'],
      ]);

      if (in_array($payment['types']['type'], ['EFFET', 'CHEQUE'])) {
         $payment['payed_at'] = $p['payed_at'];
         $payment['date_deadline'] = $p['date_deadline'];
         $payment['chq_number'] = $p['chq_number'];
         $payment['in_bank'] = $p['in_bank'];
      }
      unset($payment['id'], $payment['types'], $payment['form_bank']);
      $payment['valid'] = 0;
      $payment['done'] = 1;
      $payment['adjust_by'] = null;
      $payment['in_bank'] = null;
      $payment['state'] = null;
      $payment['typed'] = $p['typed'];

      Payment::create($payment->toArray());

      return \response()->json('OK', 200);
   }

   /**
    * @return int
    */
   public function deadlineCount()
   {
      $month = $this->req->get('month') ?? Carbon::today()->month;
      $year = $this->req->get('year') ?? Carbon::today()->year;

      return $this->repository->deadlineCount($month, $year);
   }
}
