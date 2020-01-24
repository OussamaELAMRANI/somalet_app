<?php

namespace App\Http\Controllers;

use App\ShoeSize;

class SizesController extends Controller
{
   public function index()
   {
      return response()->json(ShoeSize::orderBy('size')->get(), 200);
   }
}
