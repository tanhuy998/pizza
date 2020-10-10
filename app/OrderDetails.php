<?php

    namespace App;

    use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
    use App\Product;

    class OrderDetails extends Eloquent {

        protected $collection = 'order_details';
        protected $connection = 'mongodb';

        protected $fillable = [
            'orderId', 
            'productId', 
            'unitPrice', 
            'amount'
        ];

        public function Product() {

            return $this->hasOne(Product::class);
        }
    }