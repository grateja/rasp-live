<?php

namespace App\Jobs;

use App\RfidTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\User;
use Carbon\Carbon;

class Synch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $rfidTransaction = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(RfidTransaction $rfidTransaction)
    {
        $this->rfidTransaction = $rfidTransaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->rfidTransaction->update(['synched', Carbon::now()]);
    }
}
