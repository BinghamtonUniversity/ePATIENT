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

    public function team() {
      return $this->belongsTo(TeamMember::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }

}