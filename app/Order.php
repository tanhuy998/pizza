<?php

    namespace App;

    use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
    use App\OrderDetails;
    use App\Product;

    class Order extends Eloquent {

        protected $collection = 'Order';
        protected $connection = 'mongodb';

        protected $fillable = [
            'user_id', 'Order_Date', 'Ship_Address'
        ];

        public function User() {

            return $this->embedsOne(\App\User::class);
        }

        public function Products() {

            return $this->embedsMany(Product::class);
        }  
    }