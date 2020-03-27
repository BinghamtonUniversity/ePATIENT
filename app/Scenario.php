<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Scenario extends Model
{
    protected $fillable = ['name', 'scenario','summary_description','synopsis_description','roles'];
    protected $casts = ['scenario' => 'object','roles' => 'object'];

    public function teams() {
      return $this->hasMany(TeamMember::class);
    }
}
