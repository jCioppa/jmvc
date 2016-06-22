<?php 

    namespace App\Repositories;

    class SessionRepository extends BaseRepository {
        
        use Singleton;

        public function __construct() {

            session_start();
        }

		public function get($key) {
            if (isset($_SESSION[__CLASS__][$key]) && !is_null($_SESSION[__CLASS__][$key]))
                return $_SESSION[__CLASS__][$key][0];
            return null; 
        }

        public function set($key, $val){
            $_SESSION[__CLASS__][$key] = [$val, -1];
        }

        public function has($key) {
            if (isset($_SESSION[__CLASS__][$key]) && ! is_null($_SESSION[__CLASS__][$key])) 
                return true;
            return false;
        }

        public function flash($val) {
            
            $_SESSION[__CLASS__]['flash_message'] = [$val, 1];
        }

        public function markFlashed() {

            if ($this->has('flash_message')) {
                if ($_SESSION[__CLASS__]['flash_message'][1] == 1)  {
                    $_SESSION[__CLASS__]['flash_message'][1] = 0;
                }
            }
        }

        public function flushFlashed() {
            if ($this->has('flash_message')){
                if ($_SESSION[__CLASS__]['flash_message'][1] == 0) {
                    $_SESSION[__CLASS__]['flash_message'] = null;
                }
            }
        } 

        public function flush() {
            $_SESSION = array();
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, 
                    $params['path'], $params['domain'], 
                    $params['secure'], $params['httponly']
                );
            }
            session_destroy();
        }

    }
