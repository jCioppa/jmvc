<?php

    namespace App\Action;

    class ControllerAction extends Action {

        protected $controller;
        protected $method;

        public function __construct($c, $m){
            parent::__construct();
            $this->controller = $c;
            $this->method = $m;
        }

        public function controller() {
            return $this->controller;
        }

        public function controllerMethod() {
            return $this->method;
        }

    }
