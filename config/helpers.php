<?php

/* hashing functions {{{ */

    if(!function_exists("create_hash")) {
        function create_hash($pass, $salt, $hash_method = 'sha1') {
            if (function_exists('hash') && in_array($hash_method, hash_algos())) {
                return hash($hash_method, $salt . $pass);
            } 
            return sha1($salt . $pass);
        }
    }

    if (!function_exists("random_salt")) {
        function random_salt($len = 8) {
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789`~!@#$%^&*()-=_+';
            $l = strlen($chars) - 1;
            $str = '';
            for($i = 0; $i < $len; $i++) {
                $str .= $chars[rand(0,$l)];
            }
            return $str;
        }
    }

    if (!function_exists("validate_login")) {
        function validate_login($pass, $hashed_pass, $salt, $hash_method = 'sha1') {
            if (function_exists('hash') && in_array($hash_method, hash_algos())) {
                return ($hashed_pass == hash($hash_method, $salt . $pass));
            }
            return ($hashed_pass === sha1($salt . $pass));
        }
    }

/* }}} */

    if (! function_exists("view")) {
        function view($view) {
            return new App\Views\View($view);
        } 
    }
    
    if (!function_exists("dd")) {
        function dd($arg = null) {
            var_dump($arg);
            die();
        }
    }

    if (!function_exists("env")) {
        function env($name, $default) {

            $file = file_get_contents("../.env");
            $rows = explode("\n", $file);
            array_shift($rows);

            $ret = null;

            foreach($rows as $row) {

                $vars = explode("=", $row);

                if (is_array($vars) && ! empty($vars) && count($vars) == 2) {

                    $key = $vars[0];
                    $val = $vars[1];  

                    if ($key == $name) {
                        $ret = $val;
                        break;
                    }
                }
            } 

            if ($ret) return $ret;
            return $default;
        }
    }

    if (!function_exists("session")) {
        function session($key = false, $val = false) {
            if ($key) {
                if ($val) 
                    App\Repositories\SessionRepository::instance()->set($key, $val);
                else
                    return App\Repositories\SessionRepository::instance()->get($key);
            }
            return App\Repositories\SessionRepository::instance(); 
        }
    }

    if(!function_exists("request")) {

        function request($key = null) {
            if ($key == null)
                return App\Http\Request\Request::request();
            else 
                return App\Http\Request\Request::request()->input($key);
        }
    }

    function redirect($url) {
        return new App\Http\Redirect\Redirector($url);
    }

    function error($error){
        return redirect(SERVER . '/error')->withError($error);
    }

    function storage_path($file){ 
        return ROOT . '/storage/' . $file;
    }

    function base_path($file) {
        return app()->basePath() . "/$file";
    }
 
    function viewPage($page) {
        return App\Config::get('views.paths')[0] . "/$page.php"; 
    }
    
    function dump_array($array) {
        foreach($array as $key => $val) {
            if (is_array($val)) {
                echo($key . ' : '); 
                dump_array($val); 
                echo('<br>');
            }

            else {
                echo ($key . ' : ' . $val . '<br>');
            }
        }
    }

    function app() {
        return App\App::app();
    }


