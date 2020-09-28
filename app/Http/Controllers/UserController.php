<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\SecurityRole;
use App\Order;

class UserController extends Controller
{
    use ControllerTrait;
    //
    public function Show(Request $_request) {

        $request_page = $_request->query('page') ?? 1;
        $request_row = $_request->query('row') ?? 10;

        $db_count = User::all()->count();

        $temp = $db_count % $request_row;

        $db_pages = intval($db_count / $request_row) + ($temp === 0? 0 : 1);

        if ($db_pages < $request_page) {

            return $this->OutOfRange();
        }

        $users = User::paginate($request_row, ['*'], 'page', $request_page);

        //$ret = $ret->toArray();

        $res = [
            'pagingData' => [],
            'totalPage' => $db_pages
        ];

        foreach ($users as $user) {

            $role = SecurityRole::where('id', $user->security_role_id);

            $res['pagingData'][] = [
                "id" => $user->id,
                "name" => $user->lastName,
                "email" => $user->email,
                "phone" => $user->phoneNumber,
                "address" => $user->address ?? '',
                "roleId" => $user->security_role_id,
                "roleName" => $role->roleName
            ];
        }

        return $res;
    }

    public function GetById($id) {

        $user = User::where('_id', $id)->first();

        if (is_null($user)) {

            return $this->NotFound();
        }

        $role = SecurityRole::where('_id', $user->security_role_id)
                            ->get();

        $res = [
            "id" => $user->_id,
            "name" => $user->lastName,
            "email" => $user->email,
            "phone" => $user->phoneNumber,
            "address" => $user->address ?? '',
            "roleId" => $user->security_role_id,
            "roleName" => $role->roleName
        ];

        return $res;
    }

    public function Create(Request $_request) {

        $data = [
            'phoneNumber' => $_request->input('phone'), 
            'email' => $_request->input('email'), 
            'lastname' => $_request->input('name'), 
            'password' => $_request->input('password'), 
            'address' => $_request->input('address'),
            'security_role_id' => $_request->input('roleId')
        ];
        
        $user = new User($data);

        $user->save();

        $user->refresh();

        return [
            'id' => $user->_id
        ];
    }

    public function Update(Request $_request) {

        $user = User::where('_id', $_request->input('id'))
                    ->get();
        
        if (is_null($user)) {
            return response(['alert' => 'out of range'], 404)
            ->header('Content-Type', 'application/json');
        }

        $data = [
            'phoneNumber' => $_request->input('phone'), 
            'email' => $_request->input('email'), 
            'lastname' => $_request->input('name'), 
            //'password' => $_request->input('password'), 
            'address' => $_request->input('address'),
            'security_role_id' => $_request->input('roleId')
        ];

        $user->fill($data);

        $user->save();

        return response(['message' => 'success'], 204)
                ->header('Content-Type', 'application/json');
    }

    public function Password(Request $_request) {
        
        $user = User::where('_id', $_request->input('id'));

        if (is_null($user)) {
            return $this->NotFound();
        }

        return response(['message' => 'success'], 204)
                ->header('Content-Type', 'application/json');
    }

    public function Delete($id) {
        
        $user = User::where('_id', $id);

        if (is_null($user)) return $this->NotFound();

        $order = Order::where('user_id', $user->_id);

        if (!is_null($order)) return response(['message' => 'can not delete'], 404);

        $user->delete();

        return response([], 204);
    }
}
