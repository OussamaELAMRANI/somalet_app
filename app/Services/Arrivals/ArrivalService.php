<?php


namespace App\Services\Arrivals;


use App\Arrival;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

   public function updateArrivalToReceptions()
   {
      $id = $this->req->input('arrival_id');
      $prds = $this->req->input('products');

      $arr = Arrival::findOrFail($id);
      foreach ($prds as $p) {
         $arr->product()->wherePivot('rapport_qte', $p['pivot']['rapport_qte'])->updateExistingPivot($p['id'],
            [
               'qte_reception' => $p['pivot']['qte_reception'],
               'qte_rapport_reception' => $p['pivot']['qte_rapport_reception'],
               'date_reception' => Carbon::today()->toDateString(),
            ]);
      }

      $arr->update(['state' => 'RECEPTION']);
      return response()->json($arr->product()->get());
   }

   public function validateReception($arrival_id)
   {
      $arr = Arrival::with('product')->findOrFail($arrival_id);
      foreach ($arr['product'] as $p)
         $arr->product()->wherePivot('rapport_qte', $p['pivot']['rapport_qte'])->updateExistingPivot($p['id'],
            [
               'qte_reception' => $p['pivot']['qte_facture'],
               'qte_rapport_reception' => $p['pivot']['rapport_qte'],
               'date_reception' => Carbon::today()->toDateString(),
            ]);
      $arr->update(['state' => 'RECEPTION']);
      return response()->json(['message' => 'Reception Done !'], Response::HTTP_OK);
   }

   public function addArrival()
   {
      $arrivals = $this->req->input('arrivals');
      $prods = $this->req->input('products');

      // Add New Arrival in 1st
      $filterRequest = $arrivals;
      $filterRequest['date_facture'] = Carbon::parse($filterRequest['date_facture'])->toDate();
      $filterRequest['user_id'] = $this->req->user()->id;

      $exist = Arrival::where('n_dossier', $filterRequest['n_dossier'])->first();

      if (!$exist) {
         $res = Arrival::create($filterRequest);
         $res->saveItems($prods);
         return response()->json(['data' => $res, 'message' => "Added success"], Response::HTTP_CREATED);
      }

      return response()->json("Already Exist (Change the arrivals Container Number) !", Response::HTTP_NOT_ACCEPTABLE); //  [406]
   }

   public function addContainer()
   {
      $container = $this->req->input('container');
      $products = $this->req->input('products');


      // N_facture is Unique
      $container['date_facture'] = Carbon::parse($container['date_facture'])->toDateString();
//      $shiffle = str_shuffle("i want to show that which slots are available and which is not available");
//      $slot = substr($shiffle, 0, 6);
//      $slot = str_replace(" ", '.', $slot);
//      $container['n_facture'] = $container['n_dossier'] . "_{$slot}_" . Carbon::parse($container['date_facture'])->toDateString();
      $container['user_id'] = $this->req->user()->id;

      $exist = Arrival::where('n_dossier', $container['n_dossier'])->first();

      if (!$exist) {
         $res = Arrival::create($container);
         $res->saveItems($products);
         return response()->json(['data' => $res, 'message' => "Added success"], Response::HTTP_CREATED);
      }

      return response()->json("Already Exist (Change the arrivals NFacture )", Response::HTTP_NOT_ACCEPTABLE); // Not Acceptable [STATUE]
   }

   public function containerDetail(Arrival $container)
   {
      return $container->load('product');
   }
}
