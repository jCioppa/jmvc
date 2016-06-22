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

        public function perform($app, $response, $args = []) {

                $controller = $this->controller();
                $method = $this->controllerMethod();

                if ($app->controller_exists($controller)) {

                    $instance = new $controller;
                    
                    if ($app->class_method_exists($controller, $method)){
                        return $app->callControllerMethod($instance, $method, $args);
                    }

                    else  {
                        throw new \App\Exceptions\ServerException(500, "controller method not defined: " . $method);
                    }

                } else {
                    throw new \App\Exceptions\ServerException(500, "controller not defined: " . $controller);
                }

        }

    }
