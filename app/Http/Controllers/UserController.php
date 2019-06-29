<?php

namespace App\Http\Controllers;

use App\User;
use App\Team;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        //
    }

    public function browse()
    {
        $users = User::all();
        return $users;
    }

    public function read($user_id)
    {
        $user = User::where('id',$user_id)->first();
        if (!is_null($user)) {
            return $user;
        } else {
            return response('user_id not found', 404);
        }
    }

    public function edit(Request $request, $user_id)
    {
        $user = User::where('id',$user_id)->first();
        $user->update($request->all());
        return $user;
    }

    public function add(Request $request)
    {
        $this->validate($request,['first_name'=>['required'],'last_name'=>['required'],'email'=>['required']]);
        $user = new User($request->all());
        $user->save();
        return $user;
    }

    public function delete($user_id)
    {
        if ( User::where('id',$user_id)->delete() ) {
            return [true];
        }
    }

    public function user_teams($user_id) {
        $teams = Team::whereHas('team_members',function($query) use ($user_id) {
            $query->where('user_id', $user_id)->orWhereHas('user',function($query) use ($user_id) {
                $query->where('unique_id', $user_id);
            });
        })->with(['scenario'=>function($query){
            $query->select('id','name');
        }])->get();
        return $teams;
    }

}
