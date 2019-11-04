<?php

namespace App\Http\Controllers\Api;

use App\Bank;
use App\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class BankController extends Controller
{
   public function index()
   {
      $banks = Bank::with('payments')->groupBy('name')->get();
      return response()->json($banks, Response::HTTP_OK);
   }

   public function show($name)
   {
      $banks = Bank::where('name', $name)->with('payments', 'payments.client')->first();
      return response()->json($banks, Response::HTTP_OK);
   }

   public function storeOn(Request $request, $bank = 'if')
   {
      $payment_id = $request->get('payment_id');
      $payment = Payment::find($payment_id);
      $get_bank = Bank::Where('name', $bank)->first();

      $payment->update(['in_bank' => $get_bank->id]);

      return \response()->json("Added on {$bank}", 200);
   }

   public function externals()
   {
      $banks = Bank::where('type', 'OUT')->get();
      return response()->json($banks, Response::HTTP_OK);
   }

   public function Internals()
   {
      $banks = Bank::where('type', 'IN')->get();
      return response()->json($banks, Response::HTTP_OK);
   }
}
