<?php

    namespace App;

    use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

    class Product extends Eloquent {

        protected $collection = 'products';
        protected $connection = 'mongodb';
        protected $primaryKey = '_id';

        protected $fillable = [
            'img',
            'name',
            'price',
            'description',
            'categoryId'
        ];
    }