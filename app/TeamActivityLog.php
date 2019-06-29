<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TeamActivityLog extends Model
{
    protected $fillable = ['team_id','user_id','form','event','data'];
    protected $casts = ['data' => 'object'];
    protected $table = 'team_activity_log';

    public function team() {
      return $this->belongsTo(TeamMember::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }

}