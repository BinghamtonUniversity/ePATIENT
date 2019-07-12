<?php

namespace App\Policies;

use App\User;
use App\TeamMember;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    use HandlesAuthorization;

    public function browse(User $user) {
        return
            isset($user->permissions['manage_users']) ||
            isset($user->permissions['manage_user_permissions']) ||
            isset($user->permissions['manage_teams']) ||
            !is_null(TeamMember::where('user_id',$user->id)->where('admin',true)->first());
    }

    public function view(User $user, User $the_user) {
        return $this->browse() || $user->id === $user->id;
    }
    
    public function manage(User $user) {
        return isset($user->permissions['manage_users']);
    }

    public function manage_permissions(User $user) {
        return isset($user->permissions['manage_user_permissions']);
    }
}
