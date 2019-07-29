<?php


namespace App\Services\Arrivals;


use App\Arrival;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArrivalService
{
   private $req;

   public function __construct(Request $request)
   {
      $this->req = $request;
   }

   public function getAllArrivals()
   {
      return response()->json(Arrival::filter($this->req)->last(), 200);
   }

   public function updateArrival()
   {
      $id = $this->req->input('arrival_id');
      $prds = $this->req->input('products');

      $arr = Arrival::findOrFail($id);
      foreach ($prds as $p)
         $arr->product()->updateExistingPivot($p['id'],
            [
               'qte_reception' => $p['pivot']['qte_reception'],
               'remark_reception' => $p['pivot']['remark_reception'],
            ]);
      $arr->update(['state' => 'RECEPTION']);
      return response()->json($arr->product()->get());
   }

   public function addArrival()
   {
      $arrivals = $this->req->input('arrivals');
      $prods = $this->req->input('products');

      // Add New Arrival in 1st
      $filterRequest = $arrivals;
      $filterRequest['date_facture'] = Carbon::parse($filterRequest['date_facture'])->toDate();
      $filterRequest['user_id'] = $this->req->user()->id;

      $exist = Arrival::where('n_facture', $filterRequest['n_facture'])->first();

      if (!$exist) {
         $res = Arrival::create($filterRequest);
         $res->saveItems($prods);
         return response()->json(['data' => $res, 'message' => "Added success"], 201);
      }

      return response()->json("Already Exist (Change the arrivals NFacture )", 406); // Not Acceptable [STATUE]
   }
}
