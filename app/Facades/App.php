<?php

    namespace App\Facades;

    class App implements Facade {
        public function getFacadeAccessor() {
            return '\App\App';
        }
    }
