<?php

    return [

        'default' => 'mysql',

        'connections' => [

            'sqlite' => [
                'driver'   => 'sqlite',
                'database' => storage_path('database.sqlite'),
                'prefix'   => '',
            ],

            'mysql' => [
                'driver'    => 'mysql',
                'host'      => env('DB_HOST', 'localhost'),
                'database'  => env('DB_NAME', 'forge'),
                'username'  => env('DB_USER', 'forge'),
                'password'  => env('DB_PASS', ''),
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
                'strict'    => false,
            ],

            'pgsql' => [
                'driver'   => 'pgsql',
                'host'     => env('DB_HOST', 'localhost'),
                'database' => env('DB_NAME', 'forge'),
                'username' => env('DB_USER', 'forge'),
                'password' => env('DB_PASS', ''),
                'charset'  => 'utf8',
                'prefix'   => '',
                'schema'   => 'public',
            ],

            'sqlsrv' => [
                'driver'   => 'sqlsrv',
                'host'     => env('DB_HOST', 'localhost'),
                'database' => env('DB_NAME', 'forge'),
                'username' => env('DB_USER', 'forge'),
                'password' => env('DB_PASS', ''),
                'charset'  => 'utf8',
                'prefix'   => '',
            ]
        ]

    ];
