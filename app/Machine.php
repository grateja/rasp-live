<?php

namespace App;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Machine extends Model
{
    use SoftDeletes, UsesUuid;

    public $appends = [
        'time_ends_in', 'is_running', 'remaining_time'
    ];

    protected $fillable = [
        'id', 'client_id', 'ip_address', 'machine_type', 'total_minutes', 'time_activated', 'machine_name', 'synched', 'created_at', 'updated_at', 'deleted_at',
    ];

    public function getTimeEndsInAttribute() {
        $t = new Carbon($this->time_activated);
        return $t->addMinutes($this->total_minutes);
    }

    public function getIsRunningAttribute() {
        return $this->time_ends_in > Carbon::now();
    }

    public function getRemainingTimeAttribute() {
        if($this->isRunning) {
            $t = new Carbon($this->time_activated);
            $t->addMinutes($this->total_minutes + 1);
            return $t->diffInMinutes() . ' minute(s) remaining';
        } else {
            return 'Iddle';
        }
    }
}
