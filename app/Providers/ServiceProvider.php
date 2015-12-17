<?php

    namespace App\Providers;

    abstract class ServiceProvider {
        public function __construct() {
        }
        public abstract function register($app);
    }
