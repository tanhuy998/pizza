<?php

    namespace App;

    use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

    class User extends Eloquent {

        protected $collection = 'User';
        protected $connection = 'mongodb';

        protected $fillable = [
            'Phone_Number', 'Email', 'Lastname', 'Password', 'Firstname'
        ];
    }