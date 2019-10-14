<?php

namespace App;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductTransaction extends Model
{
    use SoftDeletes, UsesUuid;

    public function transaction() {
        return $this->belongsTo('App\Transaction');
    }

    public function product() {
        return $this->belongsTo('App\Product');
    }
}
