<?php

    namespace App;

    use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
    use App\OrderDetails;
    use App\Product;
use SebastianBergmann\CodeCoverage\Report\Xml\Totals;

class Order extends Eloquent {

        protected $collection = 'Order';
        protected $connection = 'mongodb';

        protected $fillable = [
            'address', 'securityUserId', 'totalPrice', 'note', 'orderDetailsList' //array
        ];

        public function User() {

            return $this->embedsOne(\App\User::class);
        }

        public function Products() {

            return $this->embedsMany(Product::class);
        }  
    }