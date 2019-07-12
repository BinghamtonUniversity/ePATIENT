<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserPermission extends Model
{
    protected $fillable = ['user_id', 'permission'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}