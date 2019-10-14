<?php

namespace App\Http\Controllers;

use App\RfidTransaction;
use App\ServiceTransaction;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailySalesController extends Controller
{
    public function index($year, $month) {
        $shopEmail = auth('api')->user()->email;

        $result = Transaction::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('email', $shopEmail)
            ->groupBy(DB::raw('date(created_at)'))->selectRaw('date(created_at) as created_at, SUM(price) AS total_price')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'result' => $result
        ]);
    }

    public function breakdown($date) {
        $shopEmail = auth('api')->user()->email;

        $result = Transaction::whereDate('created_at', $date)
            ->where('email', $shopEmail)
            ->groupBy('name', 'transaction_type')->selectRaw('name, transaction_type, COUNT(name) as total_count, SUM(price) AS total_price')
            ->orderByDesc('total_price')
            ->get();

        return response()->json([
            'result' => $result,
            'grandTotal' => $result->sum('total_price')
        ]);
    }

    public function rfidTransactions($date, Request $request) {
        $result = RfidTransaction::with('machine', 'rfidCard', 'servicePrice')
            ->where(function($query) use ($request) {
                $query->whereHas('machine', function($query) use ($request) {
                    $query->where('machine_name', 'like', "%$request->keyword%");
                })->orWhereHas('servicePrice', function($query) use ($request) {
                    $query->where('name', 'like', "%$request->keyword%");
                });
            })->whereDate('created_at', $date)->paginate(20);

        return response()->json([
            'rfidTransactions' => $result
        ]);
    }

    public function rfidTransactionSummary($date) {
        $result = RfidTransaction::whereDate('rfid_transactions.created_at', $date)->join('service_prices', 'rfid_transactions.service_price_id', '=', 'service_prices.id')
            ->groupBy('service_price_id', 'name')->selectRaw('name, service_price_id, SUM(price) AS total_amount, count(service_price_id) AS total_count')
            ->get();

        return response()->json([
            'summary' => $result,
            'grandTotal' => $result->sum('total_amount')
        ]);
    }

    public function servicesTransactionSummary($date) {
        $result = ServiceTransaction::with('service')->whereDate('created_at', $date)
            ->groupBy('service_id')->selectRaw('service_id, SUM(unit_price) AS total_price, SUM(quantity) AS total_quantity')
            ->get();

        return response()->json([
            'summary' => $result,
            'grandTotal' => $result->sum('total_price')
        ]);
    }
}
