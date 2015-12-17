<?php

    namespace App\Http\Route;

    class RouteAction {

        protected $method; 
        protected $action

        public function __construct($method, \App\Action\Action $action) {
            $this->method = $method;
            $this->action = $action; 
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

        public function action() {
            return $this->action;
        }

    }
