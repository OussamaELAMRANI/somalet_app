<?php

namespace App\Http\Controllers\Api;

use App\Arrival;
use App\Http\Controllers\Controller;
use App\Services\Arrivals\ArrivalService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ArrivalController extends Controller
{
   private $service;

   public function __construct(ArrivalService $arrivalService)
   {
      $this->service = $arrivalService;
   }

   public function index()
   {
      return $this->service->getAllArrivals();
   }

   public function store()
   {
      return $this->service->addArrival();
   }


   public function show(Request $request, $arrivalId)
   {
      return response()->json(Arrival::filter($request)->find($arrivalId), 200);
   }


   public function update()
   {
      return $this->service->updateArrival();
   }

   public function state(Request $request, Arrival $arr)
   {
      $arr = $arr->update(['state' => $request->input('state')]);
      return response()->json($arr);
   }


   public function destroy(Arrival $arrival)
   {
      //
   }

   public function products(Request $req)
   {
      return response()->json($req);
   }

   public function getArrivalByFactureNumber(Request $request)
   {
      $n_facture = $request->get('n_facture');
      return $n_facture;
      $arr = Arrival::where('n_facture', $n_facture)->first();
      if ($arr)
         return response()->json($arr, Response::HTTP_OK);

      return response()->json(null, Response::HTTP_NOT_FOUND);

   }
}
