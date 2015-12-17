<?php 

    namespace App\Repositories;
    
    class RouteRepository extends BaseRepository {
        
        use Singleton;
        
        private $route_type;

		static function getRoute($route) {
			return self::instance()->get($route);
        }

        static function setRoute($route, $path) {
            return self::instance()->set($route, $path);
        }

        static function getArgs($url) {
            return array();
        }

// either GET or POST

        static function getController($url) {
            $path = self::instance()->get($url);
            if (self::isGet($path)) {
                $cont = substr($path, 3);
            } else {
                $cont = substr($path, 4);
            }
            return $cont; 
        }

        static function getRouteType($url) {

            $path = self::instance()->get($url);

            if (self::isGet($path)) {
                return substr($path, 0, 3);
            } 
            return substr($path, 0, 4);
        }

// called in routes.php
        
        static function _get($route, $path){
			return self::instance()->set($route, "GET" . $path);
        }

        static function _post($route, $path) {
            return self::instance()->set($route, "POST" . $path);
        }

        public static function isGet($path) {
            return substr($path, 0, 3) == "GET";
        }

        public static function isPost($path) {
            return substr($path, 0, 4) == "POST";
        }
    
    }
