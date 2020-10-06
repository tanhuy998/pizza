<?php

    namespace App;

    use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

    class User extends Eloquent {

        protected $collection = 'security_user';
        protected $connection = 'mongodb';

        protected $fillable = [
            'phone',
            'email',
            'name',
            'password',            
            'security_role_id',
            'roles', // array 
            /*
                {
                    _id:
                    roleName:
                }
             */
            'address'
        ];
    }