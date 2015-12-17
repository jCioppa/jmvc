<?php

    namespace App\Bags;

    class ParameterBag implements \IteratorAggregate, \Countable {

        protected $parameters;

        public function __construct(array $arr) {
            $this->parameters = $arr;
        }

        public function all() {
            return $this->parameters;
        }

        public function keys() {
            return array_keys($this->parameters);
        }

        public function replace(array $params = array()) {
            $this->parameters = $params;
        }

        public function add(array $params = array() {
            $this->parameters = array_replace($this->parameters, $params);
        }

        public function set($key, $val) {
            $this->parameters[$key] = $val;
        }


                    public function get($key, $default = null, $deep = false) {
                        return null;            
                    }


        public function has($key) {
            return array_key_exists($key, $this->parameters);
        }

        public function remove($key) {
            unset($this->parameters[$key]);
        }

        public function getAlpha($key, $default = '', $deep = false) {
            return preg_replace('/[^[:alpha:]]/', '', $this->get($key, $default, $deep));
        } 

        public function getAlnum($key, $default = '', $deep = false) {
            return preg_replace('/[^[:alnum:]]/', '', $this->get($key, $default, $deep));
        } 

        public function getDigits($key, $default = '', $deep = false) {
            return str_replace(array('+', '+'), '', $this->filter($key, $default, $deep, FILTER_SANITIZE_NUMBER_INT));
        }

        public function getInt($key, $default = '', $deep = false) {
                return (int) $this->get($key, $default, $deep);
        }

        public function getBoolean($key, $default = '', $deep = false) {
                return $this->filter($key, $default, $deep, FILTER_VALIDATION_BOOLEAN);
        }





    }
