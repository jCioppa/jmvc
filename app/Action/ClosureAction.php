<?php

    namespace App\Action;

    class ClosureAction extends Action {

        protected $closure;
        protected $args = [];

        public function __construct(\Closure $c) {
            parent::__construct();
            $this->closure = $c;
        }

        public function closure() {
            return $this->closure;
        }

    }
