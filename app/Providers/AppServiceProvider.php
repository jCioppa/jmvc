<?php

    namespace App\Providers;

    class AppServiceProvider extends ServiceProvider {

        public function register($app) {

            $app->bind('App\App', function() {
                return app();
            });

            $app->bind('app', function() {
                return app();
            });

        }
    }
