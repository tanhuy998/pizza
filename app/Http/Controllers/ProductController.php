<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    use ControllerTrait;

    public function Update(Request $_request) {

        $id = $_request->input('id');

        $updated_product = Product::where('_id', $id)
                                    ->first();

        if (is_null($updated_product)) {

            return $this->NotFound();
        }

        $data = $_request->all();

        $updated_product->Update([
            'img' => $data['img'],
            'name' => $data['name'],
            'price' => $data['price'],
            'description' => $data['description'],
            'categoryId' => $data['categoryId']
        ]);

        $updated_product->save();

        return response('', 204)
            ->header('Content-Type', 'application/json');
    }

    public function Create(Request $_request) {

        //$data = $_request->all();

        $product = Product::create([
            //'img' => $data['img'],
            'name' => $_request->file('name'),//$data['name'],
            'price' => $_request->file('price'),//$data['price'],
            'description' => $_request->file('description'),//$data['description'],
            'categoryId' => $_request->file('categoryId')//$data['categoryId']
        ]);

        $product->save();

        return response('', 201);
    }

    public function Show(Request $_request) {
        
        return Product::all();
    }

    public function Delete($id) {

        $product = Product::where('_id', $id)->first();

        if (is_null($product)) return $this->NotFound();
        
        $product->delete();

        //return response(null, 200)->header('Content-Type', 'application/json');

        return response('', 200);
    }
}


