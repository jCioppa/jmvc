<?php 

    namespace App;

    class Config {

        public function __construct(){}

        public static function get($val, $default = null) {
            $vals = explode('.', $val);
            if (!$vals) return null;

            $file = $vals[0];
            $key = $vals[1];

            $path = CONFIG_FILES . "/$file" . '.php';

            if(! file_exists($path)) 
                return null;

            $settings = include ($path);

            return isset($settings[$key]) ? $settings[$key] : $default;

        }

        public static function test() {
            echo Config::get('app.name', 'default name');
        }

    }
