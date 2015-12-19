<?php

    namespace App\Http\Middleware;

    class TestMiddleware extends Middleware {

        public function handle($request, \Closure $next) {

            if (false) {
                $this->caught = true; 
                return redirect(SERVER);
            }

            return $next($request);

        }

    }
