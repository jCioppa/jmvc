<?php

    namespace App\Action;

    class ClosureAction extends Action {

        protected $closure;

        public function __construct(\Closure $c) {
            parent::__construct();
            $this->closure = $c;
        }

        public function perform() {
            return $this->closure();
        }
    }
