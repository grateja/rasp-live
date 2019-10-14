<?php

namespace App\Http\Controllers;

use App\Product;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LiveHostController extends Controller
{
    public function index($email, Request $request) {
        return DB::transaction(function () use ($request, $email) {

            $transactionIds = [];
            $productIds = [];

            if($request->transactions) {
                foreach($request->transactions as $transaction) {
                    $transaction['email'] = $email;
                    $_transaction = Transaction::find($transaction['id']);
                    if($_transaction == null) {
                        $_transaction = Transaction::create($transaction);
                    }
                    $transactionIds[] = $_transaction->id;
                }
            }

            if($request->products) {
                foreach($request->products as $product) {
                    $product['email'] = $email;
                    $_product = Product::find($product['id']);
                    if($_product != null) {
                        $_product->update([
                            'name' => $product['name'],
                            'email' => $email,
                            'stock' => $product['stock'],
                            'price' => $product['price'],
                            'card_type' => $product['card_type'],
                        ]);
                    } else {
                        $_product = Product::create($product);
                    }
                    $productIds[] = $_product->id;
                }
            }

            return response()->json([
                'transactionIds' => $transactionIds,
                'productIds' => $productIds,
            ]);
        });
    }
}
