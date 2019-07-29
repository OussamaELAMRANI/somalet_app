<?php

namespace App\Http\Controllers;

use App\Arrival;
//use App\Reception;
use Illuminate\Http\Request;

class ReceptionController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      return Arrival::WhereNotIn('state', ['RECEPTION', 'VALID'])->with('product', 'provider', 'user')->get();
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {
      //
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
      //
   }

   /**
    * Display the specified resource.
    *
    * @param \App\Reception $reception
    * @return \Illuminate\Http\Response
    */
   public function show(Reception $reception)
   {
      //
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param \App\Reception $reception
    * @return \Illuminate\Http\Response
    */
   public function edit(Reception $reception)
   {
      //
   }

   /**
    * Update the specified resource in storage.
    *
    * @param \Illuminate\Http\Request $request
    * @param \App\Reception $reception
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, Reception $reception)
   {
      //
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param \App\Reception $reception
    * @return \Illuminate\Http\Response
    */
   public function destroy(Reception $reception)
   {
      //
   }
}
