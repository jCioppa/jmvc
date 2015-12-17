<?php

    include '../config/config.php';
    if (env('APP_DEBUG', false) == true) {
        ini_set('display_errors', 'On'); 
        error_reporting(E_ALL | E_STRICT); 
    }
    
    $app = require_once "../bootstrap/app.php";

    $app->run();    

