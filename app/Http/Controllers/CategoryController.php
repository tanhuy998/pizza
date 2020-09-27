<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category as Category;

class CategoryController extends Controller
{
    //
    public function Craete(Request $_request) {

        $new_cate = new Category();

        $new_cate->Name = $_request->name;

        $new_cate->save();

        $new_cate->refresh();

        return [
            'id' => $new_cate->_id,
        ];
    }
}
