<?php

    namespace App\Http\Route;

    class RouteFactory {

        public static function create($arguments) {

            $action = $arguments["action"];
            $method = $arguments["method"];
            $options = $arguments["options"];
           
            if ($action instanceof \Closure) {
                return new Route($method, new \App\Action\ClosureAction($action), $options);
            }

            $controller = explode("@", $action)[0];
            $controllerMethod = explode("@", $action)[1];
            
            return new \App\Http\Route\Route($method, new \App\Action\ControllerAction($controller, $controllerMethod), $options);

        }

    }
