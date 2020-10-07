<?php 
    namespace App\Http\Controllers;

    trait ControllerTrait {

        public function NotFound() {
            return response(['alert' => 'resources not found'], 404)
            ->header('Content-Type', 'application/json');
        }

        public function OutOfRange() {

            return response(['alert' => 'out of range'], 404)
                ->header('Content-Type', 'application/json');
        } 
    }