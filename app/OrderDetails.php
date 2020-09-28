<?php

    namespace App;

    use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
    use App\Product;

    class OrderDetails extends Eloquent {

        protected $collection = 'OrderDetails';
        protected $connection = 'mongodb';

        protected $fillable = [
            'order_id', 'product_id', 'Unit_Price', 'Quantity'
        ];

        public function Product() {

            return $this->hasOne(Product::class);
        }
    }