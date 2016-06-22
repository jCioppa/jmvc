<?php

    namespace Commands\Input;

    class ArgvInput extends Input {

        protected $tokens;
        protected $parsed;

        public function __construct(array $tokens = []) {

            if (empty($tokens)) {
                $tokens = $_SERVER['argv'];
            } 

            array_shift($tokens);
            $this->tokens = $tokens;

        }

        public function getArgument($name) {
            return array_shift($this->tokens); 
        }

        public function getFirstArgument() {
            foreach($this->tokens as $token) {
                if ($token && $token[0] === '-')
                    continue;
                return $token;
            } 
        
        }


    }
