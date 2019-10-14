<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\Branch;

class RolesController extends Controller
{
    public function all() {
        $roles = Role::where('level', 'user')->get();

        $branches = Branch::where('client_id', auth('api')->id())->get();

        return response()->json([
            'roles' => $roles,
            'branches' => $branches,
        ], 200);
    }
}
