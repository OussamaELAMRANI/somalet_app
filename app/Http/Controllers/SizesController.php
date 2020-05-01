<?php

namespace App\Http\Controllers;

use App\ShoeSize;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SizesController extends Controller
{
   public function index()
   {
      return response()->json(ShoeSize::orderBy('size')->get(), 200);
   }

   public function store(Request $request)
   {
      $size = $request->get('size');
      $exist = ShoeSize::where('size', $size)->first();
      if ($exist) {
         return response()->json(['err' => 'This Shoe size already exist !'], Response::HTTP_BAD_REQUEST);
      }

      ShoeSize::create(['size' => $size]);
      $newSize = ShoeSize::orderBy('id', 'desc')->first();

      return response()->json($newSize, Response::HTTP_OK);
   }

   public function destroy($id)
   {
      ShoeSize::destroy($id);

      return \response()->json('Deleted successful !');
   }

}
