<?php

    namespace App;

    use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

    class Product extends Eloquent {

        protected $collection = 'Product';
        protected $connection = 'mongodb';

        protected $fillable = [
            'category_id', 'Name', 'ImgURL', 'Description', 'SizePrice', 'ProductDetails'
        ];
    }