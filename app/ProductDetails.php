<?php

    namespace App;

    use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

    class ProductDetails extends Eloquent {

        protected $collection = 'product_details';
        protected $connection = 'mongodb';

        protected $fillable = [
            'Extra_Options', 'Calculated_Price', 'product_id', 'SizePrice'
        ];
    }