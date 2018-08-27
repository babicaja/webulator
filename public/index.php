<?php

require __DIR__ . '/../vendor/autoload.php';

try {

    $app = require __DIR__ . '/../bootstrap/app.php';
    $app->run();

} catch (Exception $exception) {

    \Kucasoft\ExceptionHandler::capture($exception);
}