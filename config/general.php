<?php

return [
    "debug" => getenv("DEBUG"),
    "app" => [
        "name" => getenv("APP_NAME") ? : "Webulator",
        "version" => getenv("APP_VERSION") ? : "1.0"
    ],
    "controller" => [
        "namespace" => "\\Webulator\\HTTP\\Controllers\\",
        "errorHandler" => "RouteErrorController"
    ],
    "middleware" => [
        "namespace" => "\\Webulator\\HTTP\\Middleware\\",
    ],
    "template" => [
        "path" => "views"
    ],
    "storage" => [
        "path" => "storage"
    ]
];