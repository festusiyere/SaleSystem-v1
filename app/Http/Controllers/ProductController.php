<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $products =  auth()->user()->product()->latest()->get();

        if (!$products->isEmpty()) {
            return response()->json($products, 200);
        } else{
            return response()->json([
                "message" => "No record available"
            ], 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $unique_id = md5(microtime());
        $request['unique_id'] = $unique_id;

        try {

            $product = auth()->user()->product()->create($request->all());

            return response()->json($product, 200);

        } catch (\Throwable $th) {

            return response()->json(['message' => 'Invalid Request'], 400);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {
        $product = auth()->user()->product()->find($product);
        if ($product != null){
            return response()->json($product, 200);
        } else {
            return response()->json([
                'message' => 'No record found'
            ], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {

        if ($product != null){
            try {

                $product->quantity = $product->quantity + $request->quantity;
                $product->save();

                return response()->json($product, 200);

            } catch (\Throwable $th) {

                return response()->json([
                'message' => 'No record found'
            ], 200);

            }
        } else {
            return response()->json([
                'message' => 'No record found'
            ], 200);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($product)
    {
        $product = Product::destroy($product);
        if ($product){
            return response()->json([
                'message' => 'Deleted'
            ], 200);
        } else {
            return response()->json([
                'message' => 'No record found'
            ], 200);
        }
    }
}
