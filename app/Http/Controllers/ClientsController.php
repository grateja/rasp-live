<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Client;
use App\Branch;
use App\CityMunicipality;
use App\User;
use App\Barangay;
use App\Role;
use App\JobOrderFormat;

class ClientsController extends Controller
{
    public function autocomplete(Request $request) {
        $result = Client::with('cityMunicipality')->where('name', 'like', "%$request->keyword%")
            ->selectRaw('*, CONCAT(name, " - ", shop_name) as display')
            ->limit(10)->get();

        return response()->json([
            'data' => $result,
        ], 200);
    }

    public function update($id, Request $request) {
        $rules = [
            'name' => 'required',
            'shopName' => 'required',
            'contactNumber' => 'required',
        ];

        $client = Client::findOrFail($id);

        if($request->validate($rules)) {
            return DB::transaction(function () use ($request, $client) {
                $client->update([
                    'name' => $request->name,
                    'address' => $request->address,
                    'shop_name' => $request->shopName,
                    'address' => $request->address,
                    'contact_number' => $request->contactNumber,
                ]);

                return response()->json([
                    'client' => $client,
                ]);
            });
        }
    }

    public function store(Request $request) {
        $rules = [
            'name' => 'required',
            'shopName' => 'required',
            'contactNumber' => 'required',
        ];

        if($request->validate($rules)) {
            return DB::transaction(function () use ($request) {
                $client = Client::create([
                    'name' => $request->name,
                    'address' => $request->address,
                    'shop_name' => $request->shopName,
                    'address' => $request->address,
                    'contact_number' => $request->contactNumber,
                ]);

                return response()->json([
                    'client' => $client
                ], 200);
            });
        }
    }

    public function deleteClient($clientId) {
        $client = Client::findOrFail($clientId);
        if($client->delete()){
            return response()->json([
                'message' => 'Client deleted successfully'
            ], 200);
        }
    }
}
