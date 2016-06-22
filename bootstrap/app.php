<?php

    $app = App\App::app(
        realpath(__DIR__ . "/../")
    );

    $app->registerProviders();

    require(realpath(__DIR__ . "/../app/Http/routes.php"));

    return $app;
