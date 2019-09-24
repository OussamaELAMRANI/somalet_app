<?php


namespace App\Services\Arrivals;


use App\Arrival;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

   public function addContainer()
   {
      $container = $this->req->input('container');
      $products = $this->req->input('products');


      // N_facture is Unique
      $container['date_facture'] = Carbon::parse($container['date_facture'])->toDateString();
      $shiffle = str_shuffle("i want to show that which slots are available and which is not available");
      $slot = substr($shiffle, 0, 6);
      $slot = str_replace(" ", '.', $slot);
      $container['n_facture'] = $container['n_dossier'] . "_{$slot}_" . Carbon::parse($container['date_facture'])->toDateString();
      $container['user_id'] = $this->req->user()->id;

      $exist = Arrival::where('n_facture', $container['n_facture'])->first();

      if (!$exist) {
         $res = Arrival::create($container);
         $res->saveItems($products);
         return response()->json(['data' => $res, 'message' => "Added success"], 201);
      }

      return response()->json("Already Exist (Change the arrivals NFacture )", 406); // Not Acceptable [STATUE]
   }
}
