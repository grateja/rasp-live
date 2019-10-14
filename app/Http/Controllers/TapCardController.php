<?php

namespace App\Http\Controllers;

use App\Machine;
use App\RfidCard;
use App\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TapCardController extends Controller
{
    private function productTransaction($data) {

    }

    public function activate($ipAddress, $rfid) {
        $machine = Machine::where('ip_address', $ipAddress)
            ->first();

        $rfidCard = RfidCard::where('rfid', $rfid)->first();
        if($rfidCard == null) {
            return response()->json([
                'errors' => [
                    'rfidCard' => ['RFID Card not found']
                ]
            ], 422);
        }

        if($rfidCard->card_type == 'p') {
            $url = "$ipAddress/product";
        } else {
            $url = "$ipAddress/dimebag";
        }

        if($machine == null) {
            return response()->json([
                'errors' => [
                    'machine' => ['IP address not found']
                ]
            ], 422);
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);

        if($output) {
            return DB::transaction(function () use ($output, $machine, $rfidCard) {
                $transactionType = null;
                $transactionName = null;
                $transactionPrice = null;
                if($rfidCard->card_type == 'm') {
                    // increment total minutes
                    // check if machine already stopped
                    // if($machine->time_ends_in < Carbon::now()) {
                    if($machine->is_running) {
                        // leave activation time
                        $machine->update([
                            'total_minutes' => DB::raw('total_minutes+' . $machine->additional_time),
                        ]);
                        $transactionPrice = $machine->additional_price;
                    } else {
                        // machine already stopped
                        // activate machine
                        $machine->update([
                            'total_minutes' => $machine->initial_time,
                            'time_activated' => Carbon::now(),
                        ]);
                        $transactionPrice = $machine->initial_price;
                    }

                    $transactionType = $machine->machine_type;
                    $transactionName = $machine->machine_name;
                } else if($rfidCard->card_type == 'p') {
                    $transactionType = 'p';
                    $transactionName = $rfidCard->name;
                    $transactionPrice = $rfidCard->price;
                }

                $transaction = Transaction::create([
                    'name' => $transactionName,
                    'transaction_type' => $transactionType,
                    'price' => $transactionPrice,
                ]);
            });
        } else {
            return response()->json([
                'Machine unreachable',
            ], 404);
        }
    }

    public function test() {
        $transaction = Transaction::whereNull('remarks');
        $transaction->delete();
    }
}
