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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json(Arrival::filter($request)->last(), 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $arrivals = $req->input('arrivals');
        $prods = $req->input('products');

        // Add New Arrival in 1st
        $filterRequest = $arrivals;
        $filterRequest['date_facture'] = null;
        $filterRequest['user_id'] = 1; // todo Add User ID from Request

        $exist = Arrival::where('n_facture', $filterRequest['n_facture'])->first();

        if (!$exist) {
            $res = Arrival::create($filterRequest);
            $res->saveItems($prods, DB::getPdo()->lastInsertId());
            return response()->json(['data' => $res, 'message' => "Added success"], 201);
        }

        return response()->json("error");

        // Add its products


//        $user_info = $req->user();

//        return response()->json($arr,200);
//        $arr['date_facture'] = null;
//        $arr['user_id'] = 1; //todo Add User ID from Request
//        $filterRequest = [];
//        foreach ($arr as $k => $v) {
//            $filterRequest[$k] = $v;
//        }
//        TODO PIVO

//        $newArr = Arrival::create(request($filterRequest));
//        return response()->json($filterRequest, 200);

//        $newArr->products()->createMany($prods);
//        return response()->json('ok',200);


//        return response()->json([$req, 'user' => $user_info]);

        $n_facture = $arr['n_facture'];
        $existe = Arrival::where('n_facture', $n_facture)->first();
//        $filterRequest = $req->all();
        $filterRequest = $arr;

//        if ($req->input('type') === 'INTERNATIONAL') {
//            $filterRequest = request(['n_dossier', 'n_facture', 'price_devise', 'cours_change', 'date_facture',
//                'type', 'price_provider', 'provider_id', 'user_id',
//                'transitaire', 'transitaire_tva', 'transitaire_ttc', 'transport', 'transport_tva', 'transport_ttc',
//                ' magazinage', 'magazinage_tva', 'magazinage_ttc', 'surestaries', 'surestaries_tva', 'surestaries_ttc'
//                , 'manutension', 'manutension_tva', 'manutension_ttc', 'fret', 'fret_tva', 'fret_ttc', 'autres',
//                'autres_tva', 'autres_ttc', 'cout_revient', 'cout_revient_tva', 'cout_revient_ttc'
//            ]);
//        }
//        else {
//            $filterRequest = request(['n_dossier', 'n_facture', 'price_devise', 'cours_change', 'date_facture', 'type', 'price_provider', 'provider_id', 'user_id']);
//        }

        $filterRequest['date_facture'] = null;
        $filterRequest['user_id'] = 1; //todo Add User ID from Request

        if (!$existe) {
            $res = Arrival::create($filterRequest);
            $msg = ['message' => "Arrivage bien ajoutee"
                , 'id' => DB::getPdo()->lastInsertId()
            ];
        } else {
            $existe->update($filterRequest);
            $msg = ['message' => "Arrivage bien modifier"
                , 'id' => $existe->id
            ];
        }
        return response()->json($msg, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param \App\Arrival $arrivalId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $arrivalId)
    {
        return response()->json(Arrival::filter($request)->find($arrivalId), 200);
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