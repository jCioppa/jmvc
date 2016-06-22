<?php

    namespace Logger;

    class Registry {
            
        protected static loggers = array();

        private function __construct() {}

        public static function logger($name) {
            if (!isset(self::$loggers[$name])) {
                throw new \Exception("logger [" . $name . "] is not in the registry"); 
            }
            return self::$loggers[$name];
        }

        public static function hasLogger($name) {
            return isset(self::$loggers[$name]) && !is_null(self::$loggers[$name]);
        }

        public static function clear() {
            self::$loggers = array();
        }

        public static function addLogger($name, LoggerInterface $logger) {
            self::$loggers[$name] = $logger;
        }


    }
