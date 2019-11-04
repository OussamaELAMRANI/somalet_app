<?php

namespace App\Http\Controllers;

use App\PaymentType;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentTypeController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      return response()->json(PaymentType::all(), Response::HTTP_OK);
   }


   /**
    * Store a newly created resource in storage.
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
      $type = $request->get('type');

      $hasType = PaymentType::where('type', $type)->first();

      if (!$hasType) {
         $newType = PaymentType::create(['type'=>$type]);
         return response()->json($newType, Response::HTTP_CREATED);
      }

      return response()->json(['Error' => "Type Already exist !!"], Response::HTTP_BAD_REQUEST);

   }

   /**
    * Display the specified resource.
    *
    * @param \App\PaymentType $paymentType
    * @return \Illuminate\Http\Response
    */
   public function show(PaymentType $paymentType)
   {
      //
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param \App\PaymentType $paymentType
    * @return \Illuminate\Http\Response
    */
   public function edit(PaymentType $paymentType)
   {
      //
   }

   /**
    * Update the specified resource in storage.
    *
    * @param \Illuminate\Http\Request $request
    * @param \App\PaymentType $paymentType
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, PaymentType $paymentType)
   {
      //
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param \App\PaymentType $paymentType
    * @return \Illuminate\Http\Response
    */
   public function destroy(PaymentType $paymentType)
   {
      //
   }
}
