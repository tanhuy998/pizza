<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SecurityRole;

class RoleController extends Controller
{
    //
    public function Show() {

        $roles = SecurityRole::all();

        $res = [];

        foreach ($roles as $role) {

            $res[] = [
                "id" => $role->_id,
                "name" => $role->roleName
            ];
        }

        return $res;
    }
}
