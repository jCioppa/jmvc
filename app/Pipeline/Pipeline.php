<?php

    namespace App\Pipeline;

    class Pipeline {

        protected $container;
        protected $traveller;
        protected $stops;

        public function __construct(\App\App $app) {
            $this->container = $app;
        }

        public function send($request) {
            $this->traveller = $request;
            return $this;
        }

        public function through(array $middleware) {
            $this->stops = $middleware;
            return $this;
        }

        public function then(\Closure $destination) {

            $this->stops = array_reverse($this->stops);
            
            $f = function($next, $middleware)  {
                return function ($request) use ($middleware, $next) {
                    $mid = new $middleware;
                    $ret = $mid->handle($request, $next);
                    if ($mid->caught()) {
                        $this->container->setCaughtMiddleware(get_class($mid));
                    }
                    return $ret;
                };
            };

            $initial = function($r) use ($destination) { return $destination(); };

            $ret = array_reduce($this->stops, $f, $initial );

            return $ret($this->traveller);

        }

    }
