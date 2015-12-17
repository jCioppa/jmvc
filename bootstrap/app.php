<?php

    $app = App\App::app(
        realpath(__DIR__ . "/../")
    );

    $app->registerProviders();

    // load the contents of routes.php
    require("../app/Http/routes.php");

    return $app;
