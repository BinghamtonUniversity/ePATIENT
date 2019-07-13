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

class AdminController extends Controller
{
    public function __construct() {
    }

    public function admin(Request $request, $page=null) {
        if (is_null($page)) {
            return view('default.admin',['page'=>null, 'id'=>null,'title'=>'Admin']);
        } else {
            return view('default.admin',['page'=>$page, 'id'=>null,'title'=>ucwords($page)]);
        }
    }

    public function admin_teams(Request $request, Team $team = null, $type=null) {
        if (is_null($team)) {
            $page = 'teams';
            $title = ucwords($page);
            $id = null;
        } else {
            $page = $type;
            $title = 'Team: '.$team->name.' '.$type;
            $id = $team->id;
        }
        return view('default.admin',['page'=>$page, 'id'=>$id,'title'=>ucwords($title)]);
    }
}
