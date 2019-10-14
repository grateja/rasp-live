<?php

namespace App;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes, UsesUuid;

    protected $fillable = [
        'id', 'name', 'stock', 'price', 'card_type', 'email',
    ];
}
