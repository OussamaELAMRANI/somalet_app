<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ProductRequest;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Product::with(['unit', 'color', 'provider'])->get();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $req)
    {
        $portrait_url = '';
        if ($req->has('img') && $req->file('img')) {
            try {
                $year = Carbon::parse($req->input('date'))->year;
                $month = Carbon::parse($req->input('date'))->month;
                $portrait_url = Storage::disk('public')->putFile("articles/{$year}/{$month}", $req->file('img'));
            } catch (\Exception $e) {
                return response(['error' => $e->getMessage()]);
            }
        }
        $data = Product::create([
            'reference' => $req->input('reference'),
            'name' => $req->input('name'),
            'color_id' => $req->input('color_id'),
            'unit_id' => $req->input('unit_id'),
            'description' => $req->input('description'),
            'remark' => $req->input('remark'),
            'provider_id' => $req->input('provider_id'),
            'img' => $portrait_url,

        ]);
        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
