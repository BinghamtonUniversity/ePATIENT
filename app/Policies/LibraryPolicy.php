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
    public function manage_product(User $user) {
        return isset($user->permissions['manage_products']);
    }
    public function manage_prescriber(User $user) {
        return isset($user->permissions['manage_prescribers']);
    }
    public function manage_solution(User $user) {
        return isset($user->permissions['manage_solutions']);
    }
    public function manage_lab(User $user) {
        return isset($user->permissions['manage_labs']);
    }

}
