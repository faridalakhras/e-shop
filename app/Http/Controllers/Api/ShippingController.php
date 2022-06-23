<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shipping;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //return Product::all();
        return Shipping::when($request->query('parent_id'), function($query, $value) {
            $query->where('parent_id', '=', $value);
        })
        ->paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $shipping = Shipping::create($request->all());
        $shipping->refresh();
        return new JsonResponse($shipping, 201, [
            'x-application-name' => config('app.name'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Shipping::with('id')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $shipping = Shipping::findOrFail($id);
        $shipping ->update($request->all());
        return Response::json([
            'message' => 'Shipping update',
            'shipping' => $shipping,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shipping = Shipping::findOrFail($id);
        $shipping ->delete();
        return Response::json([
            'message' => 'Shipping deleted',
        ]);
    }
}
