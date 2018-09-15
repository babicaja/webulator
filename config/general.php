<?php

return [
    "debug" => getenv("DEBUG", false),
    "controller" => [
        "namespace" => "\\Webulator\\HTTP\\Controllers\\",
        "errorHandler" => "RouteErrorController"
    ],
    "middleware" => [
        "namespace" => "\\Webulator\\HTTP\\Middleware\\",
    ]
];