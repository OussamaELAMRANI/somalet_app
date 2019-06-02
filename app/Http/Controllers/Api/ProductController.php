<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ProductRequest;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
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
        $data = Product::with(['unit', 'color', 'provider'])->orderBy('updated_at', 'desc')->get();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $existe = Product::find($req->input('id'));
        $portrait_url = '';
        if ($req->has('img') && $req->file('img')) {
            try {
                $year = Carbon::parse($req->input('date'))->year;
                $month = Carbon::parse($req->input('date'))->month;
                $portrait_url = Storage::disk('public')->putFile("products/{$year}/{$month}", $req->file('img'));
            } catch (\Exception $e) {
                return response(['error' => $e->getMessage()]);
            }
        }
        if (!$existe) {
            $data = Product::create([
                'reference' => $req->input('reference'),
                'name' => $req->input('name'),
                'color_id' => $req->input('color_id'),
                'unit_id' => $req->input('unit_id'),
                'description' => $req->input('description'),
                'remark' => $req->input('remark'),
                'alertQte' => $req->input('alertQte'),
                'provider_id' => $req->input('provider_id'),
                'img' => $portrait_url,
            ]);
        } else {
            $data = $existe;
            $existe->update([
//                'reference' => $req->input('reference'),
                'name' => $req->input('name'),
                'color_id' => $req->input('color_id'),
                'unit_id' => $req->input('unit_id'),
                'description' => $req->input('description'),
                'remark' => $req->input('remark'),
                'provider_id' => $req->input('provider_id'),
                'img' => $portrait_url,
            ]);
        }

        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return \response()->json(Product::findOrFail($id)->load('provider', 'unit', 'color'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $id)
    {
        $v = $id::destroy($id->id);
        return response()->json(['message' => 'bien supprimé'], 201);
    }
}
