<?php

    $app->get("", "App\Controllers\IndexController@index");
    $app->get('', 'App\Controllers\IndexController@index'); 
    $app->get('index', 'App\Controllers\IndexController@index');
    $app->get('index/about', 'App\Controllers\IndexController@about');
    $app->get('error', 'App\Controllers\ErrorController@index');
    $app->post('testPost', 'App\Controllers\IndexController@testPost');
