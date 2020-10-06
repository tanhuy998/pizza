<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category as Category;

class CategoryController extends Controller
{
    //
    public function Create(Request $_request) {

        $new_cate = new Category();

        $new_cate->categoryName = $_request->input('name');

        $new_cate->save();

        $new_cate->refresh();

        return response([
            'id' => $new_cate->_id,
        ], 201);
    }

    public function Show() {
        $all = Category::all();

        return response($all->toJson(), 200)
                ->header('Content-Type', 'application/json');
    }
}
