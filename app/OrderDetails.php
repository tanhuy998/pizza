<?php

    namespace App;

    use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
    use App\Product;

    class OrderDetails extends Eloquent {

        protected $collection = 'OrderDetails';
        protected $connection = 'mongodb';

        protected $fillable = [
            'orderId', 'productId', 'unitPrice', 'amount'
        ];

        public function Product() {

            return $this->hasOne(Product::class);
        }
    }