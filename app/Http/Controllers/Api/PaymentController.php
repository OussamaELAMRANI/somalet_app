<?php

namespace App\Http\Controllers\Api;

use App\Bank;
use App\Payment;
use App\Services\Payments\PayementService;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
   private $service;

   public function __construct(PayementService $payementService)
   {
      $this->service = $payementService;
   }

   public function show($id)
   {
      $payment = Payment::with('types', 'formBank')->findOrFail($id);
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

   public function getOutstandingPayments()
   {
      return $this->service->getOutstandingPayments();
   }

   public function isOutstandingPayments($id)
   {
      $payment = Payment::find($id)->first();
      if ($payment['done']) {
         return response()->json([], 400);
      }
      return response()->json([], 200);

   }

   public function updateDeadline(Payment $payment, Request $req)
   {
      $payment->update([
         'date_deadline' => Carbon::parse($req->input('date_deadline'))->toDateString()
      ]);
      return response()->json('', Response::HTTP_OK);
   }
}
