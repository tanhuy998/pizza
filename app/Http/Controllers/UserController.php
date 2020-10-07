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

        if ($request_page > 1) {
            if ($db_pages < $request_page) {

                return $this->OutOfRange();
            }
        }
        

        $users = User::paginate($request_row, ['*'], 'page', $request_page);

        //$ret = $ret->toArray();

        $res = [
            'pagingData' => [],
            'totalPage' => $db_pages
        ];

        foreach ($users as $user) {

            //$role = SecurityRole::where('_id', $user->security_role_id)
                                //->first();

            $roles = $user->roles;
            $role = null;

            foreach($roles as $current) {

                if ($current['roleName'] === 'ADMIN') {
                    $role = $current;

                    break;
                }

                $role = $current;
            }

            $res['pagingData'][] = [
                "id" => $user->_id,
                "name" => $user->name,
                "email" => $user->email,
                "phone" => $user->phone,
                "address" => $user->address ?? '',
                "roleId" => $role['_id'],//$user->security_role_id,
                "roleName" => $role['roleName']
            ];
        }

        return $res;
    }

    public function GetById($id) {

        $user = User::where('_id', $id)->first();

        if (is_null($user)) {

            return $this->NotFound();
        }

        //$role = SecurityRole::where('_id', $user->security_role_id)
                            //->first();

        $roles = $user->roles;
        $role = null;
                
        foreach($roles as $current) {
                
            if ($current['roleName'] === 'ADMIN') {
                $role = $current;
                
                break;
            }
        }

        $res = [
            "id" => $user->_id,
            "name" => $user->name,
            "email" => $user->email,
            "phone" => $user->phone,
            "address" => $user->address ?? '',
            "roleId" => $role['_id']->__toString(),//$user->security_role_id,
            "roleName" => $role['roleName']
        ];
        
        return $res;
    }
    // set role for roles array
    public function Create(Request $_request) {
        $pass = $_request->input('password');

        $role_id = $_request->input('roleId');
        $role = SecurityRole::where('_id', $role_id)->first();
        
        if (is_null($role)) return $this->NotFound();

        $data = [
            'phone' => $_request->input('phone'), 
            'email' => $_request->input('email'), 
            'name' => $_request->input('name'), 
            'password' => bcrypt($pass), 
            'address' => $_request->input('address'),
            //'security_role_id' => $_request->input('roleId')
            'roles' => [
                '_id' => $role_id,
                'roleName' => $role->roleName
            ]
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
                    ->first();
        
        if (is_null($user)) {
            
            return $this->NotFound();
        }

        $data = [
            'phone' => $_request->input('phone'), 
            'email' => $_request->input('email'), 
            'name' => $_request->input('name'), 
            //'password' => $_request->input('password'), 
            'address' => $_request->input('address'),
            //'security_role_id' => $_request->input('roleId')
        ];

        $user->fill($data);
        
        $user->save();

        return response(['message' => 'success'], 204)
                ->header('Content-Type', 'application/json');
    }

    public function Password(Request $_request) {
        
        $user = User::where('_id', $_request->input('id'));
        $new_pass = $_request->input('newPassword');

        if (is_null($user)) {
            return $this->NotFound();
        }

        $user->password = bcrypt($new_pass);
        $user->save();
        $user->refresh();

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
