<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TeamMessage extends Model
{
    protected $fillable = ['team_id', 'user_id','message'];

    public function team() {
      return $this->belongsTo(TeamMember::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }

}