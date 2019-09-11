<?php

namespace App\Http\Controllers\Api;

use App\Provider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProviderController extends Controller
{
    /**
     * Get all providers !
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Provider::desc()->get(),200);
    }

    /**
     * Add New Provider !
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $req)
    {
        $message = null;
        $filterRequest = $this->filterStoreRequest($req);
        if ($filterRequest['id'] === 0) {
            $newProvider = Provider::create($filterRequest);
            $message = "fournisseur bien ajouter : {$newProvider->firstName}";
        } else {
            $existe = Provider::find($filterRequest['id']);
            $data = $existe;
            if ($existe) {
                $existe->update($filterRequest);
                $message = "fournisseur bien modifier : {$data->firstName}";
            } else {
                return response()->json(['message' => 'impossible de modifier ce fournisseur'], 201);
            }
        }
        return response()->json(['message' => $message], 201);
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
        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(Provider::with('products','products.color','products.unit')->findOrFail($id));
    }

    public function destroy(Provider $id)
    {
        $id::destroy($id->id);
        return response()->json(['message' => 'bien supprimé'], 201);
    }

    public function search(Request $req, $searchValue = null)
    {
        return response()->json(Provider::filter($req)->paginate(20), 200);
    }
}
