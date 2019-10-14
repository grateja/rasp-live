<?php

namespace App;

use App\Traits\UsesSynch;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes, UsesUuid, UsesSynch;

    protected $fillable = [
        'id', 'email', 'name', 'price', 'transaction_type', 'created_at'
    ];

    public function machine() {
        return $this->belongsTo('App\Machine');
    }

    public function rfidCard() {
        return $this->belongsTo('App\RfidCard');
    }

    public function servicePrice() {
        return $this->belongsTo('App\ServicePrice');
    }
}
