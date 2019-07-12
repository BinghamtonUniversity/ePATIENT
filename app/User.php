<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['first_name', 'last_name', 'email', 'unique_id'];
    protected $hidden = [
        'password', 'remember_token','perms',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $appends = ['permissions'];
    protected $with = ['perms'];

    public function team_memberships() {
        return $this->hasMany(TeamMember::class);
    }

    public function perms() {
        return $this->hasMany(UserPermission::class);
    }

    public function getPermissionsAttribute() {
        $permissions = $this->perms()->get();
        $permissions_arr = [];
        foreach($permissions as $permission) {
            $permissions_arr[$permission->permission] = true;
        }
        return $permissions_arr;
    }

}
