<?php

    namespace App\Http\Request;

    class Request {

        public $attributes;
        public $server; 
        public $files; 
        public $cookies; 
        public $headers; 
        public $input;
        protected $content;
        protected $languages;
        protected $charsets;
        protected $encodings;
        protected $acceptableContentTypes;
        protected $pathInfo;
        protected $requestUri;
        protected $baseUrl;
        protected $basePath;
        protected $method;
        protected $format;
        protected $session;
        protected $locale;
        protected $defaultLocale = 'en';
        protected static $formats;
        protected $query_string;
        private $properties;
		private $feedback = array();
		private $command;
		private $data = array();
        public static $instance;
        
        private function __construct($params = null) {
            if ($params == null) 
                $this->init();
            else {
                $this->requestUri = $params['url'];
                $this->method = $params['method'];
                $this->query_string = $params['url'];
            }
        }

        public static function make($array = null) {
            return new self($array);
        }
     


        public function init() {

            $this->headers = getallheaders();
            $this->encodings = $_SERVER['HTTP_ACCEPT_ENCODING'];
            $this->languages = explode(";", $_SERVER['HTTP_ACCEPT_LANGUAGE']);
            $this->requestUri = $_SERVER['REQUEST_URI'];
            $this->input = $_POST;
            $this->method = $_SERVER['REQUEST_METHOD'];
            $this->query_string = isset($_GET['url']) ? $_GET['url'] : null;

        }  

        protected static function initializeFormats()
        {
            self::$formats = array(
                'html' => array('text/html', 'application/xhtml+xml'),
                'txt' => array('text/plain'),
                'js' => array('application/javascript', 'application/x-javascript', 'text/javascript'),
                'css' => array('text/css'),
                'json' => array('application/json', 'application/x-json'),
                'xml' => array('text/xml', 'application/xml', 'application/x-xml'),
                'rdf' => array('application/rdf+xml'),
                'atom' => array('application/atom+xml'),
                'rss' => array('application/rss+xml'),
                'form' => array('application/x-www-form-urlencoded'),
            );
        }

        public function getFormat($mimeType)
        {
            if (false !== $pos = strpos($mimeType, ';')) {
                $mimeType = substr($mimeType, 0, $pos);
            }

            if (null === static::$formats) {
                static::initializeFormats();
            }

            foreach (static::$formats as $format => $mimeTypes) {
                if (in_array($mimeType, (array) $mimeTypes)) {
                    return $format;
                }
            }
        }

        public function getRequestFormat($default = 'html')
        {
            if (null === $this->format) {
                $this->format = $this->get('_format', $default);
            }
            return $this->format;
        }


        public function getMimeType($format)
        {
            if (null === static::$formats) {
                static::initializeFormats();
            }

            return isset(static::$formats[$format]) ? static::$formats[$format][0] : null;
        }

        public static function request() {
            if (isset(self::$instance) && ! is_null(self::$instance)) return self::$instance;
            else {
                self::$instance = new self;
                return self::$instance;
            }
        }

        public static function refresh() {
            self::$instance = new self;  
        } 

        public function input($key = null, $default = null) {

            if (!$key && !$default) return $this->input;
            
            if (isset($this->input[$key]))
                return  $this->input[$key];

            return $default;

        }

        public function query_string() {
            return $this->query_string; 
        }

        public function path() {
            return $this->query_string;
        }

        public function is($pattern) {

            preg_match(
                $pattern,
                $this->query_string,
                $matches);

            if ($matches) return true;
            return false;

        }

        public function has($name) {
            return (isset($_POST[$name]) && ! is_null($_POST[$name]));
        }

        public function all(){
           return $_POST; 
        }

        public static function allInput() {
            return self::instance()->all();
        }

        public function only(array $array) {

            $arr = array();

            foreach($_POST as $key => $val) {
                if (in_array($key, $array))
                     $arr[$key] = $val;
            }

            return $arr;

        }

        public function except(array $array) {
            $arr = array();

            foreach($_POST as $key => $val) {
                if (!in_array($key, $array))
                    $arr[$key] = $val;
            }

            return $arr;

            
        }

        public function cookie($name) {

        }

        public function url() {
            return $this->requestUri; 
        }

        public function method() {
            return $this->method; 
        }

        public function isMethod($type) {
            return ($this->method() == $type);
        }

        public function withCookie($cookie, $time) {

        }

        public function headers() {
            return $this->headers;
        }

        public function file($name) {

        }

        public function hasFile($name) {

        }

		function getProperty($key){
            if (isset($this->properties[$key])) 
                return $this->properties[$key];
            return null;
		}

		function setProperty($key, $val){
			$this->properties[$key] = $val;
		}	

		function addFeedback($msg){
			array_push($this->feedback, $msg);
		}

		function getFeedbackString($sep = "\n"){
			return implode($sep, $this->feedback);
		}

		function setCommand($cmd){
			$this->command = $cmd;
		}

		function getLastCommand(){
            return isset($this->command) ? $this->command : null;
		}

		function addData($name, $val){
			$this->data[$name] = $val;
		}

		function getData($name){
            return isset($this->data[$name]) ? $this->data[$name] : null;
        }
       
    }
