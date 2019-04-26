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
        $all = Provider::with('products')->orderBy('id', 'DESC')->get();
        return response()->json($all);
    }

    /**
     * Add New Provider !
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $req)
    {
        $filterRequest = $this->filterStoreRequest($req);

        $newProvider = Provider::create($filterRequest);

        return response()->json(['message' => "Bien Ajouter ce fournisseur ! {$newProvider->firstName}"], 201);
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

        $data = array_merge($personal, $professional, $contact);

        return $data;
    }
}
