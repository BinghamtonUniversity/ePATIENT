<?php

namespace App\Policies;

use App\User;
use App\Scenario;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScenarioPolicy
{
    use HandlesAuthorization;
    
    public function manage(User $user) {
        return isset($user->permissions['manage_scenarios']);
    }
    
}
