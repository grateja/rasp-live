<?php

namespace App\Http\Controllers;

use App\Client;
use App\Machine;
use App\RfidTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MachinesController extends Controller
{
    public function all() {
        return response()->json([
            'washers' => Machine::where('machine_type', 'rw')->orderBy('machine_name')->get(),
            'dryers' => Machine::where('machine_type', 'rd')->orderBy('machine_name')->get(),
        ]);
    }

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'ipAddress' => 'required|unique:machines,ip_address|ip',
            'machineType' => 'required',
            'machineName' => 'required',
        ];

        $clientId = Client::getId();

        if($request->validate($rules)) {
            return DB::transaction(function () use ($request, $clientId) {
                $machine = Machine::create([
                    'client_id' => $clientId,
                    'ip_address' => $request->ipAddress,
                    'machine_type' => $request->machineType,
                    'machine_name' => $request->machineName,
                ]);

                return response()->json([
                    'machine' => $machine,
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
            'ipAddress' => 'required',
            'machineType' => 'required',
            'machineName' => 'required',
        ];

        if($request->validate($rules)) {
            return DB::transaction(function () use ($request, $id) {
                $machine = Machine::findOrFail($id);
                $machine->update([
                    'ip_address' => $request->ipAddress,
                    'machine_type' => $request->machineType,
                    'machine_name' => $request->machineName,
                ]);

                return response()->json([
                    'machine' => $machine
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
        $machine = Machine::findOrFail($id);
        if($machine->delete()) {
            return response()->json([
                'message' => 'Machine deleted',
                'machine' => $machine
            ]);
        }
    }
}
