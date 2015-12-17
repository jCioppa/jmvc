<?php

    namespace App\Repositories;

    trait Singleton {

		private static $instance;

		private function __construct(){}
			
		public static function instance(){
			if (!isset(self::$instance)) self::$instance = new self();
			return self::$instance;
		}

	}

	class BaseRepository {

		private $values = array();
		
		public function get($key){
            if (isset($this->values[$key])) 
                return $this->values[$key];
			return null;	
		}

		public function set($key, $val){
			$this->values[$key] = $val;
        }

        public function values() {
            return $this->values;
        }

	}




