<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TeamNote extends Model
{
    protected $fillable = ['team_id', 'user_id','note'];

    public function team() {
      return $this->belongsTo(TeamMember::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }

}