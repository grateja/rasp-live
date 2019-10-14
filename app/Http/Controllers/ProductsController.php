<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index() {
        $email = auth('api')->user()->email;
        $result = Product::where('email', $email)->get();

        return response()->json([
            'result' => $result,
        ]);
    }
}
