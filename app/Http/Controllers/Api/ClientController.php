<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\Services\Clients\ClientService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller
{

   private $service;

   public function __construct(ClientService $clientService)
   {
      $this->service = $clientService;
   }

   public function index()
   {
      return $this->service->getAllClients();
   }


   /**
    *
    * @param Request $req
    * @return \Illuminate\Http\JsonResponse
    */
   public function store(Request $req)
   {
      $message = null;
      $filterRequest = $this->filterStoreRequest($req);
      if ($filterRequest['id'] === 0) {
         $newClient = Client::create($filterRequest);
         $message = "Client bien ajouter : {$newClient->firstName}";
      } else {
         $existe = Client::find($filterRequest['id']);
         $data = $existe;
         if ($existe) {
            $existe->update($filterRequest);
            $message = "fournisseur bien modifier : {$data->firstName}";
         } else {
            return response()->json(['message' => 'impossible de modifier ce client'], 201);
         }
      }
      return response()->json(['message' => $message], 201);
   }

   /**
    * Display the specified resource.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
   public function show($id)
   {
      return $this->service->getClientDetail($id);
   }

   public function destroy(Client $id)
   {
      $id::destroy($id->id);
      return response()->json(['message' => 'bien supprimé'], 201);
   }

   public function search(Request $req, $searchValue = null)
   {
      return response()->json(Client::filter($req)->paginate(20), 200);
   }

   /**
    * filter in coming request to Add
    *
    * @param Request $in
    * @return array
    */
   public function filterStoreRequest(Request $in)
   {
      $personal = $in->input('personal');
      $professional = $in->input('professional');
      $contact = $in->input('contact');
      $data = array_merge($personal, $professional, $contact, ['id' => $in->input('id')]);
      $data['nom'] = $data['lastName'] . ' ' . $data['firstName'];
      return $data;
   }


   public function getMovements($id)
   {
      return $this->service->getMovementClient($id);
   }

   public function getClientSold($client)
   {
      return $this->service->getClientSold($client);
   }

   public function storeDiscount(Request $req, $client)
   {
      $client = Client::find($client);
      $product_id = $req->get('product_id');
      $discount = $req->get('discount');

      $client->products()->syncWithoutDetaching([$product_id => ['discount' => $discount]]);
      return response()->json(['message' => 'Is Added successfully'], Response::HTTP_OK);
   }

   public function getClientBy(Request $req)
   {
      $by = $req->get('by');
      $search = $req->get('quote');


      if ($by == 'id') {
         return \response()->json(Client::where('id', $search)->get(), 200);
      } else {
         return \response()->json(Client::where('nom', 'like', "%{$search}%")->get(), 200);
      }
   }
}
