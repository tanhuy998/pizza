<?php

    namespace App;

    use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

    class OrderDetails extends Eloquent {

        protected $collection = 'OrderDetails';
        protected $connection = 'mongodb';

        protected $fillable = [
            'order_id', 'product_id', 'Unit_Price', 'Quantity'
        ];
    }