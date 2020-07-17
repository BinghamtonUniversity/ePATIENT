<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Library;
use App\User;
use App\Team;
use App\Scenario;

class HomeController extends Controller
{
    public function __construct()
    {
        //
    }

    public function home(Request $request) {
        $teams = Team::whereHas('team_members',function($query) {
            $query->where('user_id', Auth::user()->id);
        })->get();
        return view('home',['teams'=>$teams,'user'=>Auth::user()]);
    }

}
