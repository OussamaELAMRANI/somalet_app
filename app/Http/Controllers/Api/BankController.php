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

   public function show($name, Request $request)
   {

      if ($request->has('from') && $request->has('to')) {

         $sumTransfer = 0;
         $sumOperation = 0;

         $banks = Bank::where('name', $name)
            ->with(['payments' => function ($query) use ($sumOperation, $sumTransfer) {
               return $query->whereBetween('date_deadline', [\request('from'), \request('to')])
                  ->with('client')->orderBy('date_deadline','ASC');
            }])
            ->first();

         $payment = collect($banks)['payments'];
         foreach ($payment as $p) {
            if ($p['operation'] == 'OPR' || !$p['done']) {
               $sumOperation += $p['amount'];
            } else {
               $sumTransfer += $p['amount'];
            }
         }

      } else
         $banks = Bank::where('name', $name)
            ->with('payments', 'payments.client')->first();

      $banks['debit'] = $sumOperation;
      $banks['credit'] = $sumTransfer;

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
