<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UsesUuid;
use App\Traits\UsesSynch;
use Illuminate\Http\Exceptions\HttpResponseException;

class Client extends Model
{
    use SoftDeletes, UsesUuid;

    protected $fillable = ['name', 'shop_name', 'contact_number', 'address', 'city_municipality_id'];

    public function tncs() {
        return $this->hasMany('App\ServiceOrder')->where('service_type', 'tnc');
    }

    public function cityMunicipality() {
        return $this->belongsTo('App\CityMunicipality');
    }

    public static function getId() {
        $client = static::all();

        if($client->count() == 1) {
            return $client->first()->user_id;
        } else {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => ['One or more clients are already registered in the same machine.']
                ]
            ], 422));
        }
    }
}
