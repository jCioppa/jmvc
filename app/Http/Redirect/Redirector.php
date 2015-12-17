<?php

    namespace App\Http\Redirect;

    class Redirector {

        protected $url;
        protected $data;

        public function __construct($url, $data = array()) {
            $this->url = $url; 
            $this->data = $data;
            return $this;
        }

        public function with(array $arr) {

            foreach($arr as $key => $val) {
                $this->data[$key] = $val;
            } 

            return $this;
        }

        public function withError($error) {
            session()->flash($error); 
            return $this;
        }

        public function Redirect($stat = 303) {
            header("Location: " . $this->url, true, $stat);
            exit();
        }

    }
