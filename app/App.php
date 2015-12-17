<?php

    namespace App;

    use App\Repositories\RouteRepository;
    use App\Controllers\IndexController;
    use App\Http\Redirect\Redirector;
    use App\Container\Container;
    use \Contracts\Application\ApplicationContract;
    use \Contracts\Singleton;
    use \Contracts\HttpKernel\HttpKernelInterface;
    use App\Http\Response\Response;
    use App\Http\Request\Request;
    use App\Http\Route\Route;

    class App extends Container implements ApplicationContract, HttpKernelInterface  {

        protected static $instance;
        protected $routes = array();
        protected $basePath;
        protected $storagePath;
        protected $configPath;
        protected $databasePath;
        protected $providers = array();
        protected $aliases = array();

        private function __construct($basePath = null) {
            $this->basePath = $basePath;
            $this->storagePath = $this->basePath . '/storage';
            $this->configPath = $this->basePath . '/config';
            $this->databasePath = $this->basePath . '/database';
        } 

        public static function instance($basePath = null){
            if (!isset(self::$instance)) 
                self::$instance = new self($basePath);
			return self::$instance;
        }
    

        public function _setRoute($url, $method, \App\Action\Action $action) {
                $route = new Route($method, $action);
                $this->routes[$url] = $route;
                return $this;
        }

        public function _getRoute($url) {
            return isset($this->routes[$url]) ? $this->routes[$url] : null;
        }

        public function _get($url, \App\Action\Action $action) {
            $this->_setRoute($url, "GET", $action);
        }

        public function _post($url, \App\Action\Action $action) {
            $this->_setRoute($url, "POST", $action);
        }


    // routing {{{

        public function setRoute($url, $method, $controller, $controller_method) {
            $route = new Route($method, $controller, $controller_method);
            $this->routes[$url] = $route; 
            return $this;
        }

        public function get($url, $action) {
            $controller = explode('@', $action)[0];
            $method = explode('@', $action)[1];
            $this->setRoute($url, "GET", $controller, $method);  
        }

        public function post($url, $action) {
            $controller = explode('@', $action)[0];
            $method = explode('@', $action)[1];
            $this->setRoute($url, "POST", $controller, $method);  
        }

        public function patch($url, $action) {
            $controller = explode('@', $action)[0];
            $method = explode('@', $action)[1];
            $this->setRoute($url, "PATCH", $controller, $method);  
        }

        public function hasRoute($url) {
            return isset($this->routes[$url]);
        }

        public function getRoute($url) {
            return isset($this->routes[$url]) ? $this->routes[$url] : null;
        }

        public function getRoutes() {
            return $this->routes;
        }

        public function setRoutes(array $routes) {
            $this->routes = $routes;
        }


	// }}}	

        public function registerProviders() {
            $providers = Config::get('app.providers');  
            foreach($providers as $provider) {
                if (class_exists($provider)) {
                    array_push($this->providers, $p = new $provider);         
                    $p->register($this);
                }
            } 
        }

        public function run(Request $request = null) {
        
            session()->markFlashed();
            
            $response = $this->dispatch($request);
            
            if ($response instanceof \App\Http\Response\Response)  {
                $response->send();
            } else  {
                print_r($response);     
            }
            
            session()->flushFlashed();
            session()->set('last_page', $response->lastPage);
            $this->terminate($request, $response);

        }

        public static function app($basePath = null) {
            return self::instance($basePath);
        }

        public function dispatch(Request $request = null) {

            $method = $request ? $request->method() : $this->getMethod();
            $url = $request ? $request->query_string() : $this->getPathInfo();
            $response = new Response();

            if ($this->hasRoute($url)) {
                $response->routeDefined(true);
                return $this->handleFoundRoute($response, $method, $this->routes[$url]);
            }
        
            $response->routeDefined(false);
            $response->setStatus(404); 
            $response->setContent("<h1>Route definition fail</h1>");
            return $response;
        }

        public function handleFoundRoute(Response $response, $method, $route) {
            
            if ($method == $route->method()) {
                $response->methodClash(false);
                return $this->handleMatchedRoute($response, $route);
            } 

            $response->methodClash(true);
            $response->setStatus(404);
            $response->setContent("<h1>Method clash</h1>");
            return $response;

        }

        public function handleMatchedRoute(Response $response, $route) {
            
            $controller = $route->controller();
            $method = $route->controllerMethod(); 
            if ($this->controller_exists($controller)) {
                $instance = new $controller;
                $response->controllerExists(true);

                if ($this->class_method_exists($controller, $method)){

                    $response->methodExists(true);
                    $ret = $this->call($instance, $method);
                    $response->setStatus(200);
                    $response->setContent($ret); 
                    return $response;                                       
                }

                else  {

                    $response->methodExists(false);
                    $response->setStatus(404);
                    $response->setContent("<h1>Method error</h1>");
                    return $response;

                }
            } else {

                $response->controllerExists(false);
                $response->setStatus(404);
                $response->setContent("<h1>Controller Error</h1>");
                return $response;

            }

        }

        public function call($instance, $method, $arguments = array()) {
 
            $dependancies = $this->getDependancies(
                new \ReflectionMethod(get_class($instance), $method), 
                $arguments
            );
            
            return call_user_func_array(array($instance, $method), $dependancies); 

        } 

        public function getDependancies($reflectionClass, $arguments) {

            $params = $reflectionClass->getParameters();
            $args = array();

            foreach($params as $param) {
                $typeHint = $param->getClass();
                if($typeHint) $typeHint = $typeHint->name;

                if ($this->hasBinding($typeHint)) {
                    $arg = $this->resolve($typeHint);
                    array_push($args, $arg);
                } 
            }

            return array_merge($args, $arguments);
        }

        protected function getMethod()
        {
            if (isset($_POST['_method'])) {
                return strtoupper($_POST['_method']);
            } else {
                return $_SERVER['REQUEST_METHOD'];
            }
        }

       public function getPathInfo()
        {
            $query = isset($_GET['url']) ? $_GET['url'] : '';
            return $query;  
        }




        // ApplicationContract implementation {{{
        
        public function version() { return env("APP_VERSION", "JMVC (1.2.3)");}

        public function path() {
            return $this->basePath . DIRECTORY_SEPARATOR . 'app';
        }

        public function basePath($path = null) {
            if (isset($this->basePath))
                return $this->basePath . ($path ? '/' . $path : $path);
        }

        public function storagePath($path = null) {
            if ($this->storagePath) 
                return $this->storagePath . ($path ? '/' . $path : $path);
            return $this->basePath() . '/storage' . ($path ? '/' . $path : $path); 
        }

        public function useStoragePath($path) {
            $this->storagePath = $path;
            return $this;
        }

        public function databasePath() {
            return $this->basePath() . '/database';
        }

        public function environment() {
            $env = app("APP_ENV", "production"); 
            return $env;
        }

        public function isDownForMaintenance(){ 
            return file_exists(ROOT . "/resources/down.txt");
        }

        public function registerConfiguredProviders() {}
        public function register($provider, $options = [], $force = false) {}
        public function registerDeferredProvider($provider, $service = null) {}
        public function boot() {}
        public function booting($callback) {}
        public function booted($callback) {}
        public function getCachedCompilePath() {}
        public function getCachedServicesPath() {}
        public function welcome() { return '<h1>Welcome!</h1>';}

    // }}}
    // HTTPHandler interface {{{

        public function terminate($request, $response) {
            if ($response->hasRedirect()) {
                $response->redirect();
            }
        }
        
        public function controller_exists($controller_name) {
              if (class_exists($controller_name)){
                if (is_subclass_of($controller_name, 'App\Controllers\BaseController')){ 
                    return true; 
                } else {
                    return false; 
                }
            } else {
                return false; 
            }

        }

        public function class_method_exists($controller_name, $method) {
            return  (method_exists($controller_name, $method) && is_callable($controller_name, $method));
        }

        // }}}

    }


