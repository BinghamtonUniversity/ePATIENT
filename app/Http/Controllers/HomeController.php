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
        return view('home',['apps'=>[
                ['name'=>'ePATIENT','description'=>'
                    An educational EMR to aid in asynchronous interprofessional healthcare training',
                    'slug'=>'epatient','icon'=>'fa-address-book'],
                ['name'=>'Chem Sim','description'=>'
                    A Chemistry Simulation Toolset',
                    'slug'=>'chemsim','icon'=>'fa-flask'],
                ['name'=>'Decker EHR','description'=>'
                    A simulated Electronic Health Record System for Nursing Students',
                    'slug'=>'deckerehr','icon'=>'fa-heart']

            ],'teams'=>$teams,'user'=>Auth::user()]);
    }

}
