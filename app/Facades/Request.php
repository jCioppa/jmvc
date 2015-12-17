<?php
    
    namespace App\Facades;

    class Request implements Facade {
        public function getFacadeAccessor() {
            return '\App\Http\Request';
        }
    }
