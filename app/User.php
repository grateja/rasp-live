<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Http\Middleware\Role;
use PHPUnit\Runner\Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UsesUuid;
use App\Traits\UsesSynch;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes, UsesUuid, UsesSynch;

    public $appends = ['roles', 'fullname'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'lastname', 'firstname', 'middlename', 'contact_number', 'email', 'password', 'address', 'barangay_id', 'city_municipality_id', 'client_id', 'active_branch_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles() {
        return $this->belongsToMany('App\Role', 'role_users', 'user_id', 'role_id');
    }

    public function assignRole($roleId) {
        $this->roles()->sync($roleId);
        return $this->roles();
    }

    public function assignBranch($branchesId) {
        $this->branches()->sync($branchesId);
        return $this->branches();
    }

    public function getRolesAttribute() {
        return $this->roles()->pluck('name');
    }

    public function getFullnameAttribute() {
        return "{$this->firstname} {$this->lastname}";
    }

    public function hasAnyRole($roles) {
        foreach($this->roles as $r) {
            foreach($roles as $role) {
                if($role == $r)
                    return true;
            }
        }
        return false;
    }

    public function OauthAccessToken() {
        return $this->hasMany('App\OauthAccessToken');
    }

    public function logout() {
        return $this->OauthAccessToken()->delete();
    }

    public function client() {
        return $this->belongsTo('App\Client', 'id', 'user_id');
    }

    public function boss() {
        return $this->belongsTo('App\Client', 'client_id');
    }

    public function cityMunicipality() {
        return $this->belongsTo('App\CityMunicipality');
    }

    public function barangay() {
        return $this->belongsTo('App\Barangay');
    }

    public static function generatePassword() {
        return \str_random(8);
    }
}
