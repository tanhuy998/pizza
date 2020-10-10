<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\OrderDetails;
use App\Product;
use Illuminate\Support\Facades\Redis;
use App\Http\Middleware\JWTAuth;

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
        
        if ($request_page > 1) {
            if ($db_pages < $request_page) {
            
                return $this->OutOfRange();
            }
        }
        

        $ret = Order::paginate($request_row, ['*'], 'page', $request_page);
        
        //$ret = $ret->toArray();

        $res = [
            'pagingData' => [],
            'totalPage' => $db_pages
        ];

        foreach ($ret as $order) {
            
            $products = $order->orderDetailsList;

            $amount = 0;
            $totalPrice = 0;

            foreach($products as $product) {
                $amount += $product['amount'];
                $totalPrice += intval($product['amount'])*intval($product['unitPrice']);
            }

            $res['pagingData'][] = [
                'id' => $order->_id,
                'createAt' => $order->create_at,
                'totalPrice' => $totalPrice,
                'amount' => $amount,
                'address' => $order->address,
                'note' => $order->note
            ];
        }

        return $res;
    }

    public function GetById($id) {

        $order = Order::where('_id', $id)->first();

        if (is_null($order)) {

            return $this->NotFound();
        }

        $order_details = OrderDetails::where('_id', $order->_id)
                                        ->get();

        $amount = 0;
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
        
        foreach ($order->orderDetailsList as $detail) {

            $product = Product::where('_id' ,$detail['productId'])
                                ->first();

            
            $products[] = [
                'id' => $detail['productId'],
                'name' => $product->name ?? '',
                'price' => $detail['unitPrice'],
                'amount' => $detail['amount'],
                'size' => $detail->size ?? 'S'
            ];

            $amount += intval($detail['amount']);
            $totalPrice += intval($detail['unitPrice'])*intval($detail['amount']);
        }

        $ret = [
            'id' => $order->_id,
            'crreateAt' => $order->created_at,
            'totalPrice' => $totalPrice,
            'amount' => $amount,
            'address' => $order->address,
            'note' => $order->note ?? '',
            'name' => '',//$order->user->Lastname,
            'phone' => '',//$order->user->Phone_Number,
            'products' => $products
        ];

        return response($ret)
        ->header('Content-Type', 'application/json');
    }

    public function Create(Request $_request) {
        
        $order = new Order();

        $order->fill([
            'address' => $_request->input('address'),
            'securityUserId' => JWTAuth::GetUser()->_id,
            'note' => $_request->input('note'),
        ]);

        $products = $_request->input('products');
        $details = [];
        foreach ($products as $product) {
            $details[] = [
                'productId' => $product['id'],
                'amount' => $product['amount']
            ];
        }

        $order->orderDetailsList = $details;

        $order->save();

        $order->refresh();

        return response([
            'id' => $order->_id
        ])->header('Content-Type', 'application/json');
    }

    public function Delete($id) {


    }

    public function Update(Request $_request) {

    }


    // user order
    public function UserOrder() {
        
    }
}
