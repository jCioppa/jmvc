<?php

    namespace App\Http\Middleware;

    class AuthMiddleware extends Middleware {

        public function handle($request, \Closure $next) {

            if (app()->user() === null) {
                $this->caught = true;
                return redirect(SERVER); 
            }

            return $next($request);
        }

    }
