<?php

namespace App\Http\Controllers;

use App\User;
use App\Team;
use App\TeamMember;
use App\TeamMessage;
use App\TeamNote;
use App\TeamScenario;
use App\TeamActivityLog;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class DemoController extends Controller
{
    public function __construct() {
    }

    public function list(Request $request) {
        $users = User::where('unique_id','like','_demo%')->get();
        return view('demo_login',['users'=>$users]);
    }

    public function login(Request $request, User $user) {
        if (substr($user->unique_id, 0, 5 ) === "_demo") {
            Auth::login($user,true);
            return redirect('/');
        } else {
            return response('The Specified Account is Unauthorized', 403);
        }
    }
}
