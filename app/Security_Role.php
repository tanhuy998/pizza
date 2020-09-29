<?php

    namespace App;

    use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

    class SecurityRole extends Eloquent {

        protected $collection = 'Security_Role';
        protected $connection = 'mongodb';

        protected $fillable = [
            'roleName', 'description'
        ];
    }