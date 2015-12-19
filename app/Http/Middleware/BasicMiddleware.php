<?php

    namespace App\Http\Middleware;

    class BasicMiddleware extends Middleware {

        public function handle($request, \Closure $next) {

            if ($request->query_string() == "test") {
                $this->caught = true;
                return view("error");
            }

            return $next($request);
        }

    }
