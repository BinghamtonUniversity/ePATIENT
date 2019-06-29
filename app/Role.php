<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Role extends Model
{
    protected $fillable = ['name'];

    public function team_members() {
      return $this->hasMany(TeamMember::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
