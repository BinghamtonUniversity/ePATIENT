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
        $roles = Role::all();
        return $roles;
    }

    public function read($role_id)
    {
        $role = Role::where('id',$role_id)->first();
        if (!is_null($role)) {
            return $role;
        } else {
            return response('role_id not found', 404);
        }
    }

    public function edit(Request $request, $role_id)
    {
        $role = Role::where('id',$role_id)->first();
        $role->update($request->all());
        return $role;
    }

    public function add(Request $request)
    {
        $this->validate($request,['name'=>['required']]);
        $role = new Role($request->all());
        $role->save();
        return $role;
    }

    public function delete($role_id)
    {
        if ( Role::where('id',$role_id)->delete() ) {
            return [true];
        }
    }

}
