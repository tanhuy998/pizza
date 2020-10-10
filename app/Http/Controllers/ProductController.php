<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
//use Illuminate\Support\Facades\I

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
        $uploaded_file = $_request->file('img');
        $file = $uploaded_file->openFile();
        $content = $file->fread($file->getSize());
        $content = file_get_contents($_FILES['img']['tmp_name']);
        //return $content;
        //$file = Input::file('img')->openFile();

        //$file->fread($file->getSize());
        //$file_content = file_get_contents($file->getRealPath());
        
        $img_response = Http::withHeaders([
                                'Content-Type' => 'multipart/form-data;boundary=----WebKitFormBoundaryyrV7KO0BoCBuDbTL',
                                'Host' => 'localhost'
                            ])
                            ->attach('img', $content, $uploaded_file->getClientOriginalName())
                            ->post('localhost:3000'.'/upload');

        
        //var_dump($file);
        $res_body = $img_response->body();

        //$data = json_decode($res_body, true);
        
        return $res_body;
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


