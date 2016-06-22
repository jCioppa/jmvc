<?php

    return [

        'name' => 'josel cioppa', 
        'version' => '0.0.1',

        'providers' => [
            '\App\Providers\AppServiceProvider',
            '\App\Providers\RequestServiceProvider',
            '\App\Providers\ExceptionHandlerProvider'
        ],

        'aliases' => [
            'App'       => '\App\Facades\App',
            'Request' => 'App\Facades\Request' 
        ]

    ];
