<?php

namespace App\Http\Controllers\Api;

use App\Payment;
use App\Services\Payments\PayementService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use mysql_xdevapi\Exception;

class PaymentController extends Controller
{
   private $service;

   public function __construct(PayementService $payementService)
   {
      $this->service = $payementService;
   }

   public function show($id)
   {
      $payment = Payment::with('types', 'formBank', 'client', 'toBank')->findOrFail($id);
      return response()->json($payment);
   }

   public function store($cmd)
   {
      return $this->service->addPayment($cmd);
   }

   public function checkout()
   {
      return $this->service->getCheckout();
   }

   public function cheques()
   {
      return $this->service->getCheques();
   }

   public function getPayments()
   {
      return $this->service->getPayments();
   }

   public function getDiffPayment()
   {
      return $this->service->getPayments2Types();
   }

   public function getInvalidPayments()
   {
      return $this->service->getNoValidPayments();
   }

   public function validatePayments()
   {
      return $this->service->validatePayments();
   }

   /**
    * Transfer Money to a Specific Bank to Credit it
    *
    * @return JsonResponse
    */
   public function transfer()
   {
      return $this->service->transferMoney();
   }

   /**
    * Make un operation [AMOUNT] from Internal Bank to Debit it
    *
    * @return JsonResponse
    */
   public function operation()
   {
      return $this->service->makeOperation();
   }

   public function makeImpaye(Payment $id)
   {
      return $this->service->makeImpaye($id);
   }

   public function transferToBank($bank_id, Request $request)
   {
      $ids = $request->get('ids');

      foreach ($ids as $id) {
         $payment = Payment::find($id);
         $payment->update(['in_bank' => $bank_id]);
      }

      return response()->json('Transfer to the Bank Successfully ! ', 201);
   }

   public function transferBankToBank(Payment $payment, Request $req)
   {
      $bank_id = $req->input('in_bank');

      try {
         if (is_null($bank_id))
            throw new \Exception('Bank ID not found !');

         $payment->update(['in_bank' => $bank_id]);

      } catch (\Exception $exception) {
         return response()->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
      }

      return response()->json(['message' => 'Transferred successfully']);
   }

   public function getOutstandingPayments()
   {
      return $this->service->getOutstandingPayments();
   }

   public function isOutstandingPayments($id)
   {
      $payment = Payment::where('state', 'JST')->with('types', 'formBank')->findOrfail($id);
//      if ($payment['state'] !=='JST') {
//         return response()->json([], 400);
//      }
      return response()->json($payment, 200);

   }

   public function updateDeadline(Payment $payment, Request $req)
   {
      $payment->update([
         'date_deadline' => Carbon::parse($req->input('date_deadline'))->toDateString()
      ]);
      return response()->json('', Response::HTTP_OK);
   }


   public function adjustPayment(Payment $payment)
   {
      return $this->service->adjustPayment($payment);
   }

   /**
    * FILTER BY =>> TYPE, client, Valid, Date{from, to}
    *
    * @param Request $req
    * @return JsonResponse
    */
   public function search(Request $req)
   {
      return response()->json(Payment::filter($req)->get());
   }

   public function destroy(Payment $payment)
   {
      try {
         if (!$payment['valid'])
            $payment->delete();
         else
            throw new \Exception('Cannot delete valid Payments !');

      } catch (\Exception $e) {
         return response()->json(['message' => $e->getMessage()], Response::HTTP_PRECONDITION_FAILED);
      }

      return \response()->json(['message' => 'Delete it on success']);
   }


   public function newPayment(Request $req)
   {
      $payment = $req->input('payment');
      $payment['payed_at'] = Carbon::parse($payment['payed_at'])->toDateString();
      $payment['date_deadline'] = Carbon::parse($payment['date_deadline'])->toDateString();
      $payments = Payment::create($payment);
      return response()->json(['payment' => $payments, 'message' => 'Payment added successfully !'], 201);
   }

   public function update(Payment $payment, Request $req)
   {
      $upPayment = $req->input('payment');
      $payment->update($upPayment);
      return \response()->json($payment, Response::HTTP_ACCEPTED);
   }

   public function getDeadlineCount()
   {
      return \response()->json(['count' => $this->service->deadlineCount()], Response::HTTP_OK);
   }
}
