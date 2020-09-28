<?php 
    namespace App\Http\Controllers;

    trait ControllerTrait {

        public function NotFound() {
            response(['alert' => 'out of range'], 404)
            ->header('Content-Type', 'application/json');
        }

        public function OutOfRange() {
            response(['alert' => 'out of range'], 404)
                ->header('Content-Type', 'application/json');
        } 
    }