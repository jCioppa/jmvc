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

        public function perform() {
            $c = $this->controller;
            $m = $this->method;
            return $c->$m(); 
        }
    }
