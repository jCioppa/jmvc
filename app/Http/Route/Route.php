<?php

    namespace App\Http\Route;

    class Route {

        protected $method; 
        protected $controller;
        protected $controllermethod;

        public function __construct($method, $controller, $controllerMethod) {
            $this->method = $method;
            $this->controller = $controller;
            $this->controllermethod = $controllerMethod;
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

        public function method() {
            return $this->method;
        }

        public function controller() {
            return $this->controller;
        }

        public function controllerMethod() {
            return $this->controllermethod;
        }

        }
