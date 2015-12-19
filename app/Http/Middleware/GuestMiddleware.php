<?php

    namespace App\Http\Middleware;

    class GuestMiddleware extends Middleware {

        public function handle($request, \Closure $next) {

            if (app()->user() != null) {
                $this->caught = true;
                return view('error');
            }

            return $next($request);
        }

    }
