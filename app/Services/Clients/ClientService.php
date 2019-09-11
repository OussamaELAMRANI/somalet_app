<?php


namespace App\Services\Clients;


use App\Client;
use Illuminate\Http\Request;

class ClientService
{
   private $req;

   public function __construct(Request $request)
   {
      $this->req = $request;
   }

   private function collapseProductDiscount($products)
   {
      $filtered = [];
      foreach ($products as $p) {
         if (!array_key_exists($p['reference'], $filtered))
            $filtered[$p['reference']] = [
               'reference' => $p['reference'],
               'discount' => $p['pivot']['discount'],
            ];
      }
      return $filtered;
   }

   public function getClientDetail($id)
   {
      $clients = Client::find($id)->with('products')->first();
      $products = collect($clients->products);
      $clients['discounting'] = $this->collapseProductDiscount($products);
      return response()->json($clients, 200);
   }

   public function getAllClients()
   {
      $clients = Client::desc()->get();
      return response()->json($clients, 200);
   }
}
