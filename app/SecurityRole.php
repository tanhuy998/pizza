<?php

    namespace App;

    use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

    class SecurityRole extends Eloquent {

        protected $collection = 'security_role';
        protected $connection = 'mongodb';

        protected $fillable = [
            'roleName',
            'description',
        ];
    }