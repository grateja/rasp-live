<?php

namespace App\Http\Controllers;

use App\ServicePrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServicePricesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $services = ServicePrice::where('machine_type', $request->machineType)->get();

        return response()->json([
            'services' => $services
        ]);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($clientId, Request $request)
    {
        $rules = [
            'machineType' => 'required',
            'price' => 'required',
        ];

        $clientId = $clientId == 'self' ? auth('api')->id() : $clientId;

        if($request->validate($rules)) {
            return DB::transaction(function () use ($request, $clientId) {
                $servicePrice = ServicePrice::create([
                    'client_id' => $clientId,
                    'machine_type' => $request->machineType,
                    'price' => $request->price,
                    'is_active' => true,
                ]);

                return response()->json([
                    'servicePrice' => $servicePrice,
                ]);
            });
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $rules = [
            'machineType' => 'required',
            'price' => 'required',
        ];

        if($request->validate($rules)) {
            return DB::transaction(function () use ($request, $id) {
                $servicePrice = ServicePrice::findOrFail($id);
                $servicePrice->update([
                    'machine_type' => $request->machineType,
                    'price' => $request->price,
                    'is_active' => true,
                ]);

                return response()->json([
                    'servicePrice' => $servicePrice,
                ]);
            });
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $servicePrice = ServicePrice::findOrFail($id);
        if($servicePrice->delete()) {
            return response()->json([
                'message' => 'Service price deleted'
            ]);
        }
    }
}
