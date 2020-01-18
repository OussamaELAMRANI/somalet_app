<?php

namespace App\Http\Controllers\Api;

use App\Color;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ColorController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
   public function index()
   {
      return response()->json(Color::all(), 200);
   }


   /**
    * Store a newly created resource in storage.
    *
    * @param Request $req
    * @return Response
    */
   public function store(Request $req)
   {
      $color = Color::where(['name' => $req->input('name')])->first();

      if ($color)
         return response()->json(['hasColor' => true, 'colors' => $color], 201);

      $newColor = Color::create($req->only('name', 'color'));
      return response()->json(['message' => "Couleur a été bien ajoutée", 'hasColor' => false, 'colors' => $newColor], 201);
   }

   /**
    * Display the specified resource.
    *
    * @param Color $color
    * @return Response
    */
   public function show(Color $color)
   {
      return response()->json($color, 200);
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param Color $color
    * @return JsonResponse
    */
   public function edit(Color $color)
   {
      $color->update(request('color'));
      return response()->json('Successfull updated', 204);
   }

   /**
    * Update the specified resource in storage.
    *
    * @param Request $request
    * @param int $id
    * @return Response
    */
   public function update(Request $request, $id)
   {
      //
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param int $id
    * @return Response
    */
   public function destroy($id)
   {
      //
   }
}
