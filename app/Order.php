<?php

    namespace App;

    use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

    class Order extends Eloquent {

        protected $collection = 'Order';
        protected $connection = 'mongodb';

        protected $fillable = [
            'user_id', 'Order_Date', 'Ship_Address'
        ];
    }