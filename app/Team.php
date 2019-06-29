<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Team extends Model
{
    protected $fillable = ['name', 'scenario_id', 'team_scenario_id'];

    public function team_members() {
      return $this->hasMany(TeamMember::class);
    }

    public function team_messages() {
        return $this->hasMany(TeamMessage::class);
    }

    public function team_notes() {
        return $this->hasMany(TeamNote::class);
    }

    public function scenario() {
        return $this->belongsTo(Scenario::class);
    }

    public function team_scenario() {
        return $this->belongsTo(TeamScenario::class);
    }
}
