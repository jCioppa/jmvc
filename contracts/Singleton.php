<?php

    namespace Contracts;

    trait Singleton {

		protected static $instance;

		private function __construct(){}
			
		public static function instance(){
			if (!isset(self::$instance)) self::$instance = new self();
			return self::$instance;
        }

	}


