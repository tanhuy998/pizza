<?php

    namespace App;

    use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

    class Category extends Eloquent {

        protected $collection = 'Category';
        protected $connection = 'mongodb';

        protected $fillable = [
            'categoryName', 'ImgURL'
        ];
    }