<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\OrderDetails;
use App\Product;
use Illuminate\Support\Facades\Redis;

class OrderController extends Controller
{
    use ControllerTrait;
    //
    public function Show(Request $_request) {

        $request_page = $_request->query('page') ?? 1;
        $request_row = $_request->query('row') ?? 10;

        $db_count = Order::all()->count();

        $temp = $db_count % $request_row;

        $db_pages = intval($db_count / $request_row) + ($temp === 0? 0 : 1);

        if ($db_pages < $request_page) {

            return $this->OutOfRange();
        }

        $ret = Order::paginate($request_row, ['*'], 'page', $request_page);

        //$ret = $ret->toArray();

        $res = [
            'pagingData' => [],
            'totalPage' => $db_pages
        ];

        foreach ($ret as $order) {
            
            $res['pagingData'][] = [
                'id' => $order->_id,
                'createAt' => $order->create_at,
                'totalPrice' => '',
                'amount' => '',
                'address' => $order->shipAddress,
                'note' => ''
            ];
        }

        return $res;
    }

    public function GetById($id) {

        $order = Order::where('_id', $id)->first();

        if (is_null($order)) {

            return response('', 404)
                ->header('Content-Type', 'application/json');
        }

        $order_details = OrderDetails::where('_id', $order->_id)
                                        ->get();


        $products = [];
        $totalPrice = 0;

        // if (!is_null($order_details)) {

        //     foreach($order_details as $detail) {

        //         $product = Product::where('id', $detail->product_id);

        //         $products[] = [
        //             'id' => $product->id,
        //             'name' => $product->name,
        //             'price' => $detail->unitPrice,
        //             'amount' => $detail->quantity,
        //             'size' => $detail->size ?? 'S'
        //         ];

        //         $totalPrice += intval($detail->Unit_Price)*intval($detail->Quantity);

        //     }
        // }
        
        foreach ($order->details as $detail) {

            $product = Product::where('_id' ,$detail['product_id'])
                                ->first();

            
            $products[] = [
                'id' => $detail['product_id'],
                'name' => $product->name ?? '',
                'price' => $detail['unitPrice'],
                'amount' => $detail['quantity'],
                'size' => $detail->size ?? 'S'
            ];

            $totalPrice += intval($detail['unitPrice'])*intval($detail['quantity']);
        }

        $ret = [
            'id' => $order->_id,
            'crreateAt' => $order->created_at,
            'totalPrice' => $totalPrice,
            'amount' => '',
            'address' => $order->Ship_Adress,
            'note' => $order->note ?? '',
            'name' => '',//$order->user->Lastname,
            'phone' => '',//$order->user->Phone_Number,
            'products' => $products
        ];

        return response($ret)
        ->header('Content-Type', 'application/json');
    }

    public function Create(Request $_request) {


    }

    public function Delete($id) {


    }

    public function Update(Request $_request) {

    }
}
