<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        //
    }

    public function browse()
    {
        return array_values(config('role_permissions.roles'));
    }

    public function read($role_id)
    {
        return config('role_permissions.roles.'.$role_id);
    }

}
