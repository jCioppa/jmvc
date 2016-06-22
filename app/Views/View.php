<?php

    namespace App\Views;

    class View {

        protected $page;
        protected $args;

        public function __construct($page) {
            $this->page = $page; 
            return $this;
        }

        public function with($name, $val) {
            $this->args[$name] = $val;
            return $this;
        }

        public function __call($name, $arguments) {
        // for useing dynamic methods such as withStatus($status), withError($error), etc
            
            $with = substr($name, 0, 4);

            if ($with != 'with') 
                dd('error');

            $varname = strtolower(substr($name, 4));
            $this->args[$varname] = $arguments[0];  

            return $this;

        }

        public function getContent() {
            ob_start();
            if (!empty($this->args)){
                foreach($this->args as $key => $val) 
                    $$key = $val;
            }
            include (viewPage($this->page));
            return ob_get_clean();
        }

    }

    
