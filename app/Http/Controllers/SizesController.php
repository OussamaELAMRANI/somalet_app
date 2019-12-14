<?php

namespace App\Http\Controllers;

use App\ShoeSize;
use Illuminate\Http\Request;

class SizesController extends Controller
{
   public function index()
   {
      return response()->json(ShoeSize::all(), 200);
   }
}
