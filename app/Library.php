<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Library extends Model
{
    protected $fillable = ['type', 'data'];
    protected $casts = ['data' => 'array'];
    protected $table = 'libraries';
}
