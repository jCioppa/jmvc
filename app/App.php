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

    class App extends Container implements ApplicationContract, HttpKernelInterface {

        protected static $instance;
        protected $routes = array();
        protected $basePath;
        protected $storagePath;
        protected $configPath;
        protected $databasePath;
        protected $providers = array();
        protected $aliases = array();
        protected $caughtMiddleware = "";
        protected $environment = "browser";
        
        // global middleware {{{
        protected $middleware = [ 
            "\App\Http\Middleware\BasicMiddleware", 
            "\App\Http\Middleware\TestMiddleware" 
        ]; // }}}

        protected $user = null;

        private function __construct($basePath = null) {

            $this->basePath = $basePath;
            $this->storagePath = $this->basePath . '/storage';
            $this->configPath = $this->basePath . '/config';
            $this->databasePath = $this->basePath . '/database';

            $this->initUser();

        } 

    // user authentication {{{
        
        public function user() {
            return $this->user; 
        }

        public function initUser() {

            if (session('logged_in') && session('user_email')) {

                $user = \App\Models\User::where(['email' => session('user_email')]);

                $user_browser = $_SERVER['HTTP_USER_AGENT'];
                $login_check = hash('sha512', $user->password . $user_browser);
                $login_string = session()->get('login_string');
                
                if ($login_check === $login_string) {
                    $this->user = $user;
                } else {
                    $this->clearUser();
                }

            } else {
                $this->clearUser();
            }
        }

        public function setUser(\App\Models\User $user) {
              $this->user = $user;
        }

        public function clearUser() {

            $this->user = null;

            session()->set('logged_in', false);
            session()->set('user_email', null);
            session()->set('login_string', null);

        }

    // }}}

        public static function instance($basePath = null){
            if (!isset(self::$instance)) {
                self::$instance = new self($basePath);
            }
            
			return self::$instance;
        }

        public function setCaughtMiddleware($middleware) {
            $this->caughtMiddleware = $middleware;
        }

        public static function app($basePath = null) {
            return self::instance($basePath);
        }

        public function registerProviders() {
            $providers = Config::app('providers');  
            foreach($providers as $provider) {
                if (class_exists($provider)) {
                    $p = new $provider;
                    if ($p instanceof \App\Providers\ServiceProvider) {
                        array_push($this->providers, $p);         
                        $p->register($this);
                    }
                }
            } 
        }

        public function run(Request $request = null) {
        
            session()->markFlashed();

            $response = $this->dispatch($request);
            $response->send();

            session()->flushFlashed();
            session()->set('last_page', $response->lastPage());

            $this->terminate($request, $response);

        }


        public function dispatch(Request $request = null) {

            $method = $request ? $request->method() : $this->getMethod();
            $url = $request ? $request->query_string() : $this->getPathInfo();
            $response = new Response();

            try {

                $ret = $this->sendThroughPipeline($this->middleware, function () use ($response, $method, $url) {
                   
                    if ($this->hasRoute($url)) {
                        $args = $this->extractArgs($url);
                        return $this->handleFoundRoute($response, $method, $this->routes[$url], $args);
                    }
                    
                    throw new \App\Exceptions\HttpException(404, "page not found: " . $url);

                });

                if ($ret instanceof \App\Http\Response\Response)  {
                    return $ret;
                } else {
                    return $response->withStatus(200)->withContent($ret);
                }

            } catch (\Exception $e) {
                return $this->sendToExceptionHandler($e);
            }
        }

        public function sendToExceptionHandler(\Exception $e) {
            $handler = $this->make("handler");
            $handler->report($e);
            return $handler->render($this->make('request'), $e);
        }

        public function sendThroughPipeline($middleware, \Closure $next) {

            if (empty($middleware)) 
                return $next();
            
            return (new \App\Pipeline\Pipeline($this))
                ->send($this->make("request"))
                ->through($middleware)
                ->then($next);

        }

    /* request handling {{{ */

        public function handleFoundRoute(Response $response, $method, $route, $args = []) {

            if ($method == $route->method()) {

                $middleware = $route->middleware();

                $ret = $this->sendThroughPipeline(
                    $middleware, 
                    function () use ($response, $route, $args) { 
                        return $this->handleMatchedRoute($response, $route, $args);
                    }
                );
                
                if ($ret instanceof \App\Http\Response\Response) {
                    return $ret;
                } else {
                    return $response->withStatus(200)->withContent($ret);
                }

            } 

            throw new \App\Exceptions\ServerException(500, "method clash");

        }

        public function handleMatchedRoute(Response $response, $route, $args = []) {
            $action = $route->action(); 
            return $action->perform($this, $response, $args);
        }

        public function callControllerMethod($instance, $method, $arguments = []) {
 
            $dependancies = $this->getDependancies(
                new \ReflectionMethod(get_class($instance), $method), 
                $arguments
            );
            
            return call_user_func_array(array($instance, $method), $dependancies); 

        }

        public function callClosureAction($closure, $args = []) {
            return call_user_func($closure, $args);
        }

        public function getDependancies($reflectionClass, $arguments) {

            $params = $reflectionClass->getParameters();
            $args = array();

            foreach($params as $param) {
                $typeHint = $param->getClass();
                if($typeHint) $typeHint = $typeHint->name;

                if ($this->hasBinding($typeHint)) {
                    $arg = $this->make($typeHint);
                    array_push($args, $arg);
                } 
            }

            return array_merge($args, $arguments);
        }

        /* }}} */

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

        public function hasMiddleware() {
            return !empty($this->middleware);
        }

        public function setEnvironment($env) {
            $this->environment = $env;
        }

        public function environment() {
            return $this->environment;
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

        public function env() {
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
    // routing {{{

        public function hasRoute($url) {
            if(isset($this->routes[$url])) return true;
            return false;
        }
    
        public function extractArgs($url) {
            return [];
        }

        public function setRoute($url, $method, \App\Action\Action $action) {
            $route = new Route($method, $action);
            $this->routes[$url] = $route; 
            return $this;
        }

        public function get($url, $action, $options = []) {
            $this->routes[$url] = \App\Http\Route\RouteFactory::create(["method" => "GET", "action" => $action, 'options' => $options]);
        }

        public function post($url, $action, $options = []) {
            $this->routes[$url] = \App\Http\Route\RouteFactory::create(["method" => "POST", "action" => $action, 'options' => $options]);
        }

        public function patch($url, $action, $options = []) {
            $this->routes[$url] = \App\Http\Route\RouteFactory::create(["method" => "PATCH", "action" => $action, 'options' => $options]);
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

    }


