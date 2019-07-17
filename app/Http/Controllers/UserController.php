<?php

namespace App\Http\Controllers;

use App\User;
use App\Team;
use App\UserPermission;
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

    public function read(User $user)
    {
        return $user;
    }

    public function edit(Request $request, User $user)
    {
        $user->update($request->all());
        return $user;
    }

    public function add(Request $request)
    {
        $this->validate($request,['first_name'=>['required'],'last_name'=>['required']]);
        $user = new User($request->all());
        $user->save();
        return $user;
    }

    public function delete(User $user)
    {
        if ( User::where('id',$user->id)->delete() ) {
            return [true];
        }
    }

    public function user_teams(User $user) {
        $teams = Team::whereHas('team_members',function($query) use ($user) {
            $query->where('user_id', $user->id)->orWhereHas('user',function($query) use ($user) {
                $query->where('unique_id', $user->id);
            });
        })->with(['scenario'=>function($query){
            $query->select('id','name');
        }])->get();
        return $teams;
    }

    public function update_permissions(Request $request, User $user) {
        UserPermission::where('user_id',$user->id)->delete();

        foreach($request->permissions as $permission) {
            $permission = new UserPermission(['user_id'=>$user->id,'permission'=>$permission]);
            $permission->save();
        }
        return User::where('id',$user->id)->first();
    }

}
