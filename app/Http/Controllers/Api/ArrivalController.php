<?php

namespace App\Http\Controllers\Api;

use App\Arrival;
use App\Http\Controllers\Controller;
use App\Services\Arrivals\ArrivalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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

   public function newContainer()
   {
      return $this->service->addContainer();
   }


   public function show(Request $request, $arrivalId)
   {
      return response()->json(Arrival::filter($request)->find($arrivalId), 200);
   }


   public function update()
   {
      return $this->service->updateArrivalToReceptions();
   }

   public function validateReception($arrival)
   {
      return $this->service->validateReception($arrival);
   }

   public function state(Request $request, Arrival $arr)
   {

      if ($arr['state'] == 'VUE' || $arr['state'] == 'ATTENTE') {
         $arr = $arr->update(['state' => $request->input('state')]);
         return response()->json($arr, Response::HTTP_OK);
      }

      return response()->json(['message' => 'It\'s already see it !'], Response::HTTP_BAD_REQUEST);

   }


   public function destroy(int $arrival_id)
   {
      $isDelete = Arrival::destroy($arrival_id);
      return ($isDelete)
         ? response()->json('Delete it', 200)
         : response()->json('Cannot delete it !', 402);
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


   public function getContainerDetail(Arrival $container)
   {
      return $this->service->containerDetail($container);
   }

   /**
    * Update no valid Container
    *
    * @param Arrival $container
    * @return JsonResponse
    */
   public function updateContainer(Arrival $container)
   {
      $upContainer = request()->get('container');
      $arrivalFields = ['n_dossier', 'date_facture', 'provider_id'];
      // Update Container fields
      foreach ($arrivalFields as $field) {
         $container->update([
            $field => ($field == 'date_facture') ?
               Carbon::parse($upContainer[$field])->toDate()
               : $upContainer[$field]]);
      }
      $collection = collect($container);
      // update arrival_products
      if ($collection->get('state') !== 'VALID') {
         $products = collect(request('products'));
         $container->product()->detach();
         foreach ($products as $product) {
            $container->product()->attach($product['product_id'], [
               'qte_facture' => $product['qte_facture'],
               'rapport_qte' => $product['rapport_qte'],
               'price_unit_ht' => $product['price_unit_ht'],
            ]);
         }
      }
      // Change the state to notify the storekeeper
      $container->update(['state' => 'VUE']);

      return response()->json($container);
   }
}
