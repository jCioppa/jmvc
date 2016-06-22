<?php

$app->get("", "App\Controllers\IndexController@index");
$app->post("testPost", "App\Controllers\IndexController@testPost");
$app->get("hello", function() {return "hello world!";});
$app->get("error", "App\Controllers\ErrorController@index");

/* authentication routes */

$app->get("auth/login", "App\Controllers\Auth\LoginController@index",[
    'middleware' => ['App\Http\Middleware\GuestMiddleware']
]);

$app->get(
    "auth/register", 
    "App\Controllers\Auth\RegisterController@index", 
    [
        'middleware' => ['App\Http\Middleware\GuestMiddleware'],
        'as' => 'register'
    ]
);

$app->get("auth/logout", "App\Controllers\Auth\LoginController@logout", 
[
    'middleware' => ["App\Http\Middleware\AuthMiddleware"]
]);

$app->post("login", "App\Controllers\Auth\LoginController@login");
$app->post("register", "App\Controllers\Auth\RegisterController@register");

