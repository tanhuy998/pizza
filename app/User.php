<?php

    namespace App;

    use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

    class User extends Eloquent {

        protected $collection = 'User';
        protected $connection = 'mongodb';

        protected $fillable = [
            'phoneNumber', 'email', 'lastName', 'password', 'firstName', 'security_role_id', 'address'
        ];
    }