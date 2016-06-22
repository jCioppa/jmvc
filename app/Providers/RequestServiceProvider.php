<?php

    namespace App\Providers;

    class RequestServiceProvider extends ServiceProvider {
        public function register($app) {
            $app->bind('App\Http\Request\Request', function() { return request(); });
            $app->bind('request', function() { return request(); });
        }
    }
