<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use App\Barangay;
use App\CityMunicipality;
use App\SelfAuth;
use App\Rules\VerifyPassword;
use App\Role;

class UsersController extends Controller
{
    public function index($userId, Request $request) {
        $userId = $userId == 'self' ? auth('api')->user()->client_id : $userId;

        $users = User::with('branches')->where(function($query) use ($request) {
            $query->where('lastname', 'like', "%$request->keyword%")
                ->orWhere('firstname', 'like', "%$request->keyword%");
        })->where('client_id', $userId);

        return response()->json([
            'result' => $users->paginate(10)
        ], 200);
    }

    public function autocomplete($clientId, Request $request) {
        $clientId = $clientId == 'self' ? auth('api')->user()->client_id : $clientId;

        $data = User::where(function($query) use ($request) {
            $query->where('lastname', 'like', "%$request->keyword%")
                ->orWhere('firstname', 'like', "%$request->keyword%");
        })->whereHas('branches', function($query) {
            $query->where('id', auth('api')->user()->active_branch_id);
        })->where('client_id', $clientId)->limit(10)->get();

        $data = collect($data)->map(function($item) {
            return $item;
        });

        return response()->json([
            'data' => $data
        ], 200);
    }

    public function create($clientId, Request $request) {
        $clientId = $clientId == 'self' ? auth('api')->user()->client_id : $clientId;
        $rules = [
            'lastname' => 'required',
            'firstname' => 'required',
            'contactNumber' => 'required',
            'email' => 'required|email|unique:users|confirmed',
            'password' => 'required|min:5|confirmed',
        ];

        if($request->validate($rules)) {
            return DB::transaction(function () use ($request, $clientId) {
                $barangay = null;
                $cityMunicipality = null;

                if($request->barangay) {
                    $barangay = Barangay::firstOrCreate([
                        'name' => $request->barangay,
                    ]);
                }

                if($request->cityMunicipality) {
                    $cityMunicipality = CityMunicipality::firstOrCreate([
                        'name' => $request->cityMunicipality,
                    ]);
                }

                $user = User::create([
                    'lastname' => $request->lastname,
                    'firstname' => $request->firstname,
                    'middlename' => $request->middlename,
                    'email' => $request->email,
                    'address' => $request->address,
                    'contact_number' => $request->contactNumber,
                    'barangay_id' => $barangay ? $barangay->id : null,
                    'city_municipality_id' => $cityMunicipality ? $cityMunicipality->id : null,
                    'password' => bcrypt($request->password),
                    'client_id' => $clientId,
                ]);

                $user->assignRole(Role::where('name', 'staff')->first()->id);
                $user->assignBranch(auth('api')->user()->active_branch_id);

                return response()->json([
                    'user' => $user
                ], 200);
            });
        }
    }

    public function assignRole($userId, Request $request) {
        $client = auth()->user();

        $rules = [
            'roleId' => 'required',
            'password' => ['required', new VerifyPassword($client->password)],
            'branchesId' => 'required'
        ];

        $user = User::findOrFail($userId);

        SelfAuth::check($user->client_id);

        if($userId == $client->id) {
            return response()->json([
                'errors' => [
                    'roleId' => ['Cannot assign role to self.']
                ]
            ], 403);
        }

        if($request->validate($rules)) {
            return DB::transaction(function () use ($request, $user) {
                $user->assignRole($request->roleId);
                $user->assignBranch($request->branchesId);

                return response()->json([
                    'user' => $user->fresh('branches'),
                ], 200);
            });
        }
    }
}
