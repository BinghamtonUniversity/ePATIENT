<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TeamMember extends Model
{
    protected $fillable = ['user_id', 'team_id', 'role_id', 'admin'];

    protected $casts = [
        'admin' => 'bool',
    ];
    protected $appends = ['role_title','role_permissions'];

    public function team() {
      return $this->belongsTo(TeamMember::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getRoleTitleAttribute() {
        return config('role_permissions.roles.'.$this->role_id.'.title');
    }

    public function getRolePermissionsAttribute() {
        return config('role_permissions.permissions.'.config('role_permissions.roles.'.$this->role_id.'.permissions'));
    }


}