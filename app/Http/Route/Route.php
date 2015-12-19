<?php

    namespace App\Http\Route;

    class Route {

        protected $method; 
        protected $action;

        protected $middleware = [];
        protected $alias = null;

        public function __construct($method, \App\Action\Action $action, array $options = [])
        {

            $this->method = $method;
            $this->action = $action;

            if (isset($options["middleware"]) && is_array($options["middleware"]) && !empty($options["middleware"])) {
                $this->middleware = $options["middleware"];
            }

            if (isset($options["as"]) && !is_null($options["as"])) {
                $this->alias = $options["as"];
            }

        }

        public function isGet() {
            return $this->method == "GET";
        }

        public function isPost() {
            return $this->method == "POST";
        }

        public function setGet() {
            $this->method = "GET";
        }

        public function setPost() {
            $this->$method = "POST";
        }

        public function action() {
            return $this->action;
        }

        public function method() {
            return $this->method;
        }

        public function hasMiddleware() {
            return !empty($this->middleware);
        }

        public function addMiddleware($middleware) {
            array_push($this->middleware, $middleware);
        }

        public function middleware() {
            return $this->middleware;
        }

        public function isControllerAction() {
            return ($this->action() instanceof \App\Action\ControllerAction);
        }

        public function isClosureAction() {
            return ($this->action() instanceof \App\Action\ClosureAction);
        }

    }
