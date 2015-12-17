<?php

    namespace App\Action;

    abstract class Action {
        public function __construct(){}    
        public abstract function perform(); 
    }
