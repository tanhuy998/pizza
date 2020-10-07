<?php

    namespace App;

    use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

    class Category extends Eloquent {

        protected $collection = 'categories';
        protected $connection = 'mongodb';

        protected $fillable = [
            'categoryName', 'ImgURL'
        ];
    }