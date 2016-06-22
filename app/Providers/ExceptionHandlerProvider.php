<?php

    namespace App\Providers;

    class ExceptionHandlerProvider extends ServiceProvider {

        public function register($app) {

            $app->bind('Contracts\Debug\ExceptionHandlerInterface', function() {
                return new \App\Debug\ExceptionHandler(); 
            });

            $app->bind('handler', function() {
                return new \App\Debug\ExceptionHandler(); 
            });

        }
    }
