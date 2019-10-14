<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Barangay;
use App\CityMunicipality;
use App\Client;
use DB;
use App\Rules\VerifyPassword;
use App\SelfAuth;

class AccountsController extends Controller
{
    public function updateProfile($id, Request $request) {
        if($id == 'self') {
            $user = auth('api')->user();
        } else {
            $user = User::findOrFail($id);
        }

        $rules = [
            'lastname' => 'required',
            'firstname' => 'required',
            'contactNumber' => 'required',
            'cityMunicipality' => 'required'
        ];

        if($request->validate($rules)) {
            return DB::transaction(function () use ($request, $user) {

                $user->update([
                    'lastname' => $request->lastname,
                    'middlename' => $request->middlename,
                    'firstname' => $request->firstname,
                    'contact_number' => $request->contactNumber,
                ]);

                return response()->json([
                    'user' => $user,
                ], 200);
            });
        }

    }

    public function updateEmail($id, Request $request) {
        if($id == 'self') {
            $id = auth('api')->id();
        }

        $user = User::findOrFail($id);

        $rules = [
            'email' => 'required|email',
            'emailConfirmation' => 'required',
            'password' => ['required', new VerifyPassword($user->password)],
        ];

        if($request->email != $user->email) {
            // email changed
            $rules['email'] = 'required|email|unique:users';
        }


        if($request->validate($rules)) {
            if($request->email != $request->emailConfirmation) {
                return response()->json([
                    'errors' => [
                        'emailConfirmation' => ['Email confirmation does not matched']
                    ]
                ], 422);
            }

            // check password here
            $user->update([
                'email' => $request->email,
            ]);

            return response()->json([
                'user' => $user,
            ], 200);

        }
    }

    public function updatePassword($id, Request $request) {
        if($id == 'self') {
            $id = auth('api')->id();
        }

        $user = User::findOrFail($id);

        $rules = [
            'oldPassword' => ['required', new VerifyPassword($user->password)],
            'password' => 'required|min:5|confirmed',
        ];

        if($request->validate($rules)) {
            return DB::transaction(function () use ($user, $request) {
                $user->update([
                    'password' => bcrypt($request->password)
                ]);

                $client = Client::where('user_id', $user->id)->first();

                if(!$client->account_activated) {
                    $client->update([
                        'account_activated' => true,
                        'initial_password' => null,
                    ]);
                }

                return response()->json([
                    'user' => $user
                ]);
            });
        }
    }

    public function getAccountInfo($id) {
        if($id == 'self') {
            $id = auth('api')->id();
        }

        $user = User::findOrFail($id);

        return response()->json([
            'accountInfo' => $user
        ], 200);
    }
}
