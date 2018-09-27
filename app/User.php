<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Laravel\Passport\HasApiTokens;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use HasApiTokens, Authenticatable, Authorizable;

    protected $fillable = [
        'avatar','name', 'email',
    ];

    protected $hidden = [
        'id', 'password',
    ];

    protected $appends = [
        'userid'
    ];

    public function getUseridAttribute()
    {
        return $this->id;
    }


    public function orders()
    {
        return $this->hasMany('App\Order');
    }
}
