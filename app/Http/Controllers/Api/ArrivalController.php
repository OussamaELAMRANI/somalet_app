<?php

namespace App\Http\Controllers\Api;

use App\Arrival;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArrivalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $user_info = $req->user();

//        return response()->json([$req, 'user' => $user_info]);

        $n_facture = request('n_facture');
        $existe = Arrival::where('n_facture', $n_facture)->first();
        $filterRequest = null;
        if (\request('type') === 'INTERNATIONAL') {
            $filterRequest = \request(['n_dossier', 'n_facture', 'price_devise', 'cours_change', 'date_facture',
                'type', 'price_provider', 'provider_id', 'user_id',
                'transitaire', 'transitaire_tva', 'transitaire_ttc', 'transport', 'transport_tva', 'transport_ttc',
                ' magazinage', 'magazinage_tva', 'magazinage_ttc', 'surestaries', 'surestaries_tva', 'surestaries_ttc'
                , 'manutension', 'manutension_tva', 'manutension_ttc', 'fret', 'fret_tva', 'fret_ttc', 'autres',
                'autres_tva', 'autres_ttc', 'cout_revient', 'cout_revient_tva', 'cout_revient_ttc'
            ]);
        } elseif (\request('type') === 'NATIONAL') {
            $filterRequest = \request(['n_dossier', 'n_facture', 'price_devise', 'cours_change', 'date_facture', 'type', 'price_provider', 'provider_id', 'user_id']);
        } else {
            //$filterRequest = \request(['n_dossier','n_facture','price_devise','cours_change','date_facture','type','price_provider','provider_id','user_id']);
        }
        $filterRequest['date_facture'] = null;
        $filterRequest['user_id'] = 1; //todo
        if (!$existe) {
            $res = Arrival::create($filterRequest);
            $msg = ['message' => "Arrivage bien ajoutee"
                    ,'id'=>DB::getPdo()->lastInsertId()
            ];
        } else {
            $existe->update($filterRequest);
            $msg = ['message' => "Arrivage bien modifier"
                ,'id'=>$existe->id
            ];
        }
        return response()->json($msg, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Arrival $arrival
     * @return \Illuminate\Http\Response
     */
    public function show(Arrival $arrival)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Arrival $arrival
     * @return \Illuminate\Http\Response
     */
    public function edit(Arrival $arrival)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Arrival $arrival
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Arrival $arrival)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Arrival $arrival
     * @return \Illuminate\Http\Response
     */
    public function destroy(Arrival $arrival)
    {
        //
    }

    public function products(Request $req)
    {
        return response()->json($req);
    }

}
