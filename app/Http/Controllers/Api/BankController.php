<?php

namespace App\Http\Controllers\Api;

use App\Bank;
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
      $banks = Bank::where('name', $name)->with('payments','payments.client')->first();
      return response()->json($banks, Response::HTTP_OK);
   }
}
