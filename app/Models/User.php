<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use App\Traits\ParseTimeStamp;
use Tymon\JWTAuth\Contracts\JWTSubject;
class User extends Authenticatable implements JWTSubject
{
    use ParseTimeStamp;
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'password', 'role', 'created_time', 'updated_time'
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
    /*Hash password*/
    public function setPasswordAttribute($password){
        if ( !empty($password) ) {
            $this->attributes['password'] = Hash::make($password);
        }
    }

    public function isAdmin(){
        return ($this->role == 'Admin')? true :false;
    }

    public function isManager(){
        return($this->role == 'Manager')? true :false;
    }


    /**
     * @inheritDoc
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @inheritDoc
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    // ======================================================================
    // Relationships
    // ======================================================================
    public function schedules(){
        return $this->hasMany(Schedule::class);
    }
}
