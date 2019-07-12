<?php

namespace App\Policies;

use App\User;
use App\Team;
use App\TeamMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;
    
    public function browse(User $user) {
        return 
            isset($user->permissions['manage_teams']) ||
            !is_null(TeamMember::where('user_id',$user->id)->where('admin',true)->first());
    }

    public function view(User $user, Team $team) {
        // If you can manage teams or you are a team admin or you are a team member
        return 
            isset($user->permissions['manage_teams']) ||
            !is_null(TeamMember::where('user_id',$user->id)->where('team_id',$team->id)->first());
    }
    
    public function manage(User $user, Team $team=null) {
        if (is_null($team)) {
            return isset($user->permissions['manage_teams']);
        } else {
            return 
                isset($user->permissions['manage_teams']) ||
                !is_null(TeamMember::where('user_id',$user->id)->where('team_id',$team->id)->where('admin',true)->first());
        }
    }
}
