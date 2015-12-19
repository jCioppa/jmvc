<?php

    namespace App\Http\Middleware;

    abstract class Middleware {

        protected $caught = false;

        public function caught() {
            return $this->caught;
        }

        public abstract function handle($request, \Closure $next);
    }
