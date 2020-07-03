<?php

namespace App\Http\Controllers;

use App\Sale;
use App\Product;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales = auth()->user()->sale()->latest()->get();
        if(!$sales->isEmpty()){
            return response()->json($sales, 200);
        }{
            return response()->json([
                'message' => 'No record Yet'
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
        $unique_id = strtoupper(md5(time()));
        $prefix = substr($unique_id, rand(1, 20), 2);
        $sufix = substr($unique_id, rand(1, 20), 10);
        $unique_id = $prefix.'-'.$sufix;
        $request['ref_no'] = $unique_id;

        foreach ($request['details'] as  $value) {
            $this->updateRecord($value['id'], $value['quantity']);
        }

        try {

            $sale = auth()->user()->sale()->create($request->all());

            return response()->json($sale, 200);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Invalid Request'
            ], 400);

        }
    }

    private function updateRecord($id, $update)
    {
        $product = auth()->user()->product()->find($id);
        $product->quantity =  $product->quantity - $update;
        $product->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function show($sale)
    {
        $record = auth()->user()->sale()->find($sale);

        if($record != null){
            return response()->json($record, 200);
        }{
            return response()->json([
                'message' => 'Record not found'
            ], 200);
        }


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function edit(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sale $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $sale)
    {
        dd($sale);
    }

    public function reverseSale(Request $request, Sale $sale) {

        $sales = auth()->user()->sale()->find($sale->id);
        if(!$sales){
            return response()->json([
            'message' => 'Sale Record not found'
        ], 200);

        } else{

            foreach ($request->details as $value) {
                $this->addBack($value);
            }

            Sale::destroy($sale->id);

            return response()->json([
                'message' => 'Sale Reveresed Successfully'
            ], 200);
        }
    }

    private function addBack($value){

        try {
            $product = auth()->user()->sale()->find($value['id']);
            $product->quantity = $product->quantity + $value['quantity'];
            $product->save();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function editSale(Request $request) {

        $data = $request->all();
        $old = $data[0];
        $new = $data[1];

        try {
            foreach ($old['details'] as $valueOld) {
                foreach ($new['details'] as $valueNew) {

                    if ($valueOld['id'] == $valueNew['id']) {
                        $this->remove($valueOld, $valueNew);
                    } else {
                        $this->addBack($valueOld);
                    }
                }
            }
            $sale = auth()->user()->sale()->find($old['id'])->update($new);
            return response()->json([
                'message' => 'Sale Updated Successfully'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error Occured'
            ], 200);
        }

    }

    private function remove($old, $new) {

        try {
            $product = auth()->user()->product()->find($old['id']);

            if ($old['quantity'] > $new['quantity']) {
                $diff = $old['quantity'] - $new['quantity'];
                $product->quantity = $product->quantity + $diff;
            }

            if ($old['quantity'] < $new['quantity']) {
                $diff =$new['quantity'] - $old['quantity'];
                $product->quantity = $product->quantity - $diff;
            }

            $product->save();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
