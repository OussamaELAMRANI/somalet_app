<?php

namespace App\Http\Controllers\Api;

use App\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Client::desc()->get(),200);
    }  //


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
        return \response()->json(Client::findOrFail($id));
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
        $data['nom'] = $data['lastName'].$data['firstName'];
        return $data;
    }

}