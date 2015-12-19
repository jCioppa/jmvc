<?php


    $app = App\App::app(
        realpath(__DIR__ . "/../")
    );

    $app->registerProviders();

    require("../app/Http/routes.php");

    return $app;
