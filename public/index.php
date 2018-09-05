<?php

require __DIR__ . '/../vendor/autoload.php';

try {

    // Boot the app.
    $app = require __DIR__ . '/../bootstrap/app.php';

    // Define routes for the application.
    $app->routes()->get("/", "HomeController@welcome");
    $app->routes()->post("/post/{id}", "HomeController@welcome");

    // Execute.
    $app->run();

} catch (Exception $exception) {

    \Webulator\ExceptionHandler::capture($exception);
}