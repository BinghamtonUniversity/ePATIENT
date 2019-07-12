<?php

namespace App\Policies;

use App\User;
use App\Library;
use Illuminate\Auth\Access\HandlesAuthorization;

class LibraryPolicy
{
    use HandlesAuthorization;

    public function manage(User $user) {
        $library_type = request()->route()->parameters['library_type'];
        return isset($user->permissions['manage_'.$library_type]);
    }
}
